<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            // Use different default for SQLite vs PostgreSQL
            if (DB::getDriverName() === 'sqlite') {
                $table->uuid('id')->primary();
            } else {
                $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            }
            
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->integer('amount'); // en centavos
            $table->string('currency')->default('MXN');
            $table->string('plan'); // free, professional, unlimited
            
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->string('method')->default('card'); // card, transfer, cash
            
            // Stripe IDs
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->string('stripe_subscription_id')->nullable()->index();
            $table->string('stripe_invoice_id')->nullable()->index();
            
            $table->string('receipt_url')->nullable();
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
