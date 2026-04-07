<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            
            // Producto y ubicación
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_lot_id')->nullable()->constrained('product_lots')->onDelete('cascade');
            
            // Cantidad reservada
            $table->decimal('quantity', 15, 4);
            $table->decimal('quantity_released', 15, 4)->default(0);
            $table->decimal('quantity_consumed', 15, 4)->default(0);
            
            // Origen de la reserva
            $table->string('reservable_type'); // SalesOrder, ProductionOrder, etc.
            $table->uuid('reservable_id');
            
            // Estado
            $table->enum('status', ['active', 'partially_released', 'fully_released', 'expired', 'cancelled'])->default('active');
            
            // Expiración
            $table->timestamp('expires_at')->nullable();
            
            // Notas
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['tenant_id', 'status']);
            $table->index(['product_id', 'warehouse_id', 'status']);
            $table->index(['reservable_type', 'reservable_id']);
            $table->index(['expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reservations');
    }
};
