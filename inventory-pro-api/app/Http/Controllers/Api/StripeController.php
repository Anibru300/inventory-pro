<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\PaymentIntent;
use Stripe\Invoice;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crear intento de pago para un plan
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:professional,unlimited',
            'payment_method_id' => 'nullable|string',
        ]);

        $tenant = $request->user()->tenant;
        $plan = $request->plan;
        $amount = Tenant::PRICES[$plan];

        try {
            // Crear o obtener cliente de Stripe
            $customer = $this->getOrCreateCustomer($tenant, $request->user());

            // Crear PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'mxn',
                'customer' => $customer->id,
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => false,
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'user_id' => $request->user()->id,
                    'plan' => $plan,
                ],
            ]);

            // Crear registro de pago pendiente
            $payment = Payment::create([
                'tenant_id' => $tenant->id,
                'user_id' => $request->user()->id,
                'amount' => $amount,
                'currency' => 'MXN',
                'plan' => $plan,
                'status' => Payment::STATUS_PENDING,
                'method' => Payment::METHOD_CARD,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'metadata' => [
                    'client_secret' => $paymentIntent->client_secret,
                ],
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe PaymentIntent error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar el pago'], 500);
        }
    }

    /**
     * Crear suscripción con trial
     */
    public function createSubscription(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:professional,unlimited',
            'payment_method_id' => 'required|string',
        ]);

        $tenant = $request->user()->tenant;
        $plan = $request->plan;

        // Plan profesional tiene 1 mes de trial
        $trialDays = $plan === Tenant::PLAN_PROFESSIONAL ? 30 : 0;

        try {
            // Crear o obtener cliente
            $customer = $this->getOrCreateCustomer($tenant, $request->user());

            // Adjuntar método de pago al cliente
            \Stripe\PaymentMethod::retrieve($request->payment_method_id)->attach([
                'customer' => $customer->id,
            ]);

            // Crear suscripción
            $subscription = Subscription::create([
                'customer' => $customer->id,
                'items' => [[
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'Inventory Pro - ' . ucfirst($plan),
                        ],
                        'unit_amount' => Tenant::PRICES[$plan],
                        'recurring' => [
                            'interval' => 'month',
                        ],
                    ],
                ]],
                'default_payment_method' => $request->payment_method_id,
                'trial_period_days' => $trialDays,
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'plan' => $plan,
                ],
            ]);

            // Actualizar tenant
            $tenant->update([
                'plan' => $plan,
                'stripe_customer_id' => $customer->id,
                'stripe_subscription_id' => $subscription->id,
                'trial_ends_at' => $trialDays > 0 ? now()->addDays($trialDays) : null,
                'subscription_ends_at' => null,
            ]);

            $tenant->applyPlanLimits();
            $tenant->save();

            // Crear registro de pago
            Payment::create([
                'tenant_id' => $tenant->id,
                'user_id' => $request->user()->id,
                'amount' => 0, // Trial
                'currency' => 'MXN',
                'plan' => $plan,
                'status' => Payment::STATUS_COMPLETED,
                'method' => Payment::METHOD_CARD,
                'stripe_subscription_id' => $subscription->id,
                'period_start' => now(),
                'period_end' => $trialDays > 0 ? now()->addDays($trialDays) : now()->addMonth(),
                'notes' => $trialDays > 0 ? 'Inicio de trial de 30 días' : 'Suscripción creada',
            ]);

            return response()->json([
                'success' => true,
                'subscription_id' => $subscription->id,
                'trial_ends_at' => $tenant->trial_ends_at,
                'message' => $trialDays > 0 
                    ? 'Suscripción creada con 30 días de prueba gratis'
                    : 'Suscripción creada exitosamente',
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Subscription error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear la suscripción'], 500);
        }
    }

    /**
     * Cancelar suscripción
     */
    public function cancelSubscription(Request $request)
    {
        $tenant = $request->user()->tenant;

        if (!$tenant->stripe_subscription_id) {
            return response()->json(['error' => 'No hay suscripción activa'], 400);
        }

        try {
            $subscription = Subscription::retrieve($tenant->stripe_subscription_id);
            $subscription->cancel();

            // El webhook actualizará el tenant cuando la suscripción termine

            return response()->json([
                'success' => true,
                'message' => 'Suscripción cancelada. Seguirás teniendo acceso hasta el final del período pagado.',
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe Cancel error: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cancelar la suscripción'], 500);
        }
    }

    /**
     * Obtener configuración de Stripe
     */
    public function getConfig()
    {
        return response()->json([
            'key' => config('services.stripe.key'),
            'prices' => Tenant::PRICES,
        ]);
    }

    /**
     * Obtener historial de pagos
     */
    public function getPaymentHistory(Request $request)
    {
        $payments = Payment::where('tenant_id', $request->user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($payments);
    }

    /**
     * Webhook de Stripe
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info('Stripe Webhook received: ' . $event->type);

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Crear pago por transferencia bancaria
     */
    public function createTransferPayment(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:professional,unlimited',
            'reference' => 'required|string|max:50',
        ]);

        $tenant = $request->user()->tenant;
        $plan = $request->plan;
        $amount = Tenant::PRICES[$plan];

        // Crear pago pendiente por transferencia
        $payment = Payment::create([
            'tenant_id' => $tenant->id,
            'user_id' => $request->user()->id,
            'amount' => $amount,
            'currency' => 'MXN',
            'plan' => $plan,
            'status' => Payment::STATUS_PENDING,
            'method' => Payment::METHOD_TRANSFER,
            'metadata' => [
                'reference' => $request->reference,
                'bank_account' => config('services.bank.account'),
                'bank_name' => config('services.bank.name'),
            ],
            'notes' => 'Pago por transferencia bancaria - Referencia: ' . $request->reference,
        ]);

        return response()->json([
            'success' => true,
            'payment_id' => $payment->id,
            'bank_info' => [
                'account' => config('services.bank.account'),
                'name' => config('services.bank.name'),
                'holder' => config('services.bank.holder'),
                'clabe' => config('services.bank.clabe'),
            ],
            'message' => 'Por favor realiza la transferencia y envía el comprobante. Tu cuenta será actualizada en 24-48 horas hábiles.',
        ]);
    }

    // Métodos privados auxiliares

    private function getOrCreateCustomer($tenant, $user)
    {
        if ($tenant->stripe_customer_id) {
            try {
                return Customer::retrieve($tenant->stripe_customer_id);
            } catch (\Exception $e) {
                // Cliente no existe, crear nuevo
            }
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $tenant->name,
            'phone' => $tenant->phone,
            'metadata' => [
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
            ],
        ]);

        $tenant->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    private function handleInvoicePaymentSucceeded($invoice)
    {
        $subscriptionId = $invoice->subscription;
        $tenant = Tenant::where('stripe_subscription_id', $subscriptionId)->first();

        if (!$tenant) {
            Log::warning('Tenant not found for subscription: ' . $subscriptionId);
            return;
        }

        // Crear registro de pago
        Payment::create([
            'tenant_id' => $tenant->id,
            'amount' => $invoice->amount_paid,
            'currency' => strtoupper($invoice->currency),
            'plan' => $tenant->plan,
            'status' => Payment::STATUS_COMPLETED,
            'method' => Payment::METHOD_CARD,
            'stripe_invoice_id' => $invoice->id,
            'stripe_subscription_id' => $subscriptionId,
            'receipt_url' => $invoice->hosted_invoice_url,
            'period_start' => now()->setTimestamp($invoice->period_start),
            'period_end' => now()->setTimestamp($invoice->period_end),
        ]);

        // Actualizar fecha de fin de suscripción
        $tenant->update([
            'subscription_ends_at' => now()->setTimestamp($invoice->period_end),
        ]);
    }

    private function handleInvoicePaymentFailed($invoice)
    {
        $subscriptionId = $invoice->subscription;
        $tenant = Tenant::where('stripe_subscription_id', $subscriptionId)->first();

        if ($tenant) {
            Payment::create([
                'tenant_id' => $tenant->id,
                'amount' => $invoice->amount_due,
                'currency' => strtoupper($invoice->currency),
                'plan' => $tenant->plan,
                'status' => Payment::STATUS_FAILED,
                'method' => Payment::METHOD_CARD,
                'stripe_invoice_id' => $invoice->id,
                'notes' => 'Pago fallido: ' . ($invoice->last_payment_error->message ?? 'Error desconocido'),
            ]);
        }
    }

    private function handleSubscriptionDeleted($subscription)
    {
        $tenant = Tenant::where('stripe_subscription_id', $subscription->id)->first();

        if ($tenant) {
            $tenant->update([
                'plan' => Tenant::PLAN_FREE,
                'stripe_subscription_id' => null,
                'subscription_ends_at' => now(),
            ]);
            $tenant->applyPlanLimits();
            $tenant->save();
        }
    }

    private function handleSubscriptionUpdated($subscription)
    {
        $tenant = Tenant::where('stripe_subscription_id', $subscription->id)->first();

        if ($tenant && $subscription->cancel_at_period_end) {
            // La suscripción se cancelará al final del período
            $tenant->update([
                'subscription_ends_at' => now()->setTimestamp($subscription->current_period_end),
            ]);
        }
    }
}
