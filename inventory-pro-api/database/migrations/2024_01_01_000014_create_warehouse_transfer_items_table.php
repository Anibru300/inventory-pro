<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_transfer_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('warehouse_transfer_id')->constrained()->onDelete('cascade');
            
            // Producto
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_lot_id')->nullable()->constrained('product_lots')->onDelete('set null');
            
            // Cantidades
            $table->decimal('quantity_requested', 15, 4);
            $table->decimal('quantity_sent', 15, 4)->nullable();
            $table->decimal('quantity_received', 15, 4)->nullable();
            
            // Costo al momento del envío
            $table->decimal('unit_cost', 15, 4);
            $table->decimal('total_cost', 15, 2);
            
            // Estado individual del ítem
            $table->enum('status', [
                'pending',
                'sent',
                'received',
                'partially_received',
                'damaged',
                'missing'
            ])->default('pending');
            
            // Notas específicas
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['warehouse_transfer_id', 'status']);
            $table->index(['product_id']);
            $table->index(['product_lot_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_transfer_items');
    }
};
