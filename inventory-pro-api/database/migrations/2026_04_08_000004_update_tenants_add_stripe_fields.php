<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Stripe fields
            if (!Schema::hasColumn('tenants', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->after('stripe_id')->index();
            }
            if (!Schema::hasColumn('tenants', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->after('stripe_subscription_id')->index();
            }
        });

        // Update existing tenants to free plan
        DB::table('tenants')->whereNotIn('plan', ['free', 'professional', 'unlimited'])->update([
            'plan' => 'free',
            'max_warehouses' => 1,
            'max_products' => 100,
            'max_users' => 1,
        ]);
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'stripe_subscription_id')) {
                $table->dropColumn('stripe_subscription_id');
            }
            if (Schema::hasColumn('tenants', 'stripe_customer_id')) {
                $table->dropColumn('stripe_customer_id');
            }
        });
    }
};
