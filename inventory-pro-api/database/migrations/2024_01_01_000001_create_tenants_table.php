<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Enable UUID extension (PostgreSQL only)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        }
        
        Schema::create('tenants', function (Blueprint $table) {
            // Use different default for SQLite vs PostgreSQL
            if (DB::getDriverName() === 'sqlite') {
                $table->uuid('id')->primary();
            } else {
                $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            }
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('logo_url')->nullable();
            
            // Subscription
            $table->string('plan')->default('starter');
            $table->string('status')->default('active');
            $table->timestamp('subscription_ends_at')->nullable();
            
            // Regional settings
            $table->string('timezone')->default('America/Mexico_City');
            $table->string('currency')->default('MXN');
            $table->string('language')->default('es');
            
            // Limits
            $table->json('settings')->nullable();
            $table->integer('max_users')->default(1);
            $table->integer('max_products')->default(500);
            $table->integer('max_warehouses')->default(1);
            
            // Stripe
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
