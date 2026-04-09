<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            
            $table->string('type'); // shopify, woocommerce, mercadolibre
            $table->string('name');
            $table->enum('status', ['active', 'paused', 'error'])->default('active');
            
            // API Credentials (encrypted)
            $table->text('credentials'); // JSON encrypted
            $table->text('settings')->nullable(); // JSON
            
            // Sync settings
            $table->boolean('sync_products')->default(true);
            $table->boolean('sync_stock')->default(true);
            $table->boolean('sync_orders')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->text('last_sync_error')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'status']);
        });

        // Integration logs
        Schema::create('integration_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('integration_id')->constrained()->onDelete('cascade');
            
            $table->string('action'); // sync_products, sync_stock, etc.
            $table->enum('status', ['success', 'error', 'warning']);
            $table->text('message')->nullable();
            $table->json('details')->nullable();
            $table->integer('records_processed')->default(0);
            
            $table->timestamps();
            
            $table->index(['integration_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integration_logs');
        Schema::dropIfExists('integrations');
    }
};
