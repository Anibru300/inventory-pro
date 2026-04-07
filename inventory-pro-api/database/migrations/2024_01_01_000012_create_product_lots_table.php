<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_lots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('warehouse_id')->constrained()->onDelete('cascade');
            
            // Información del lote
            $table->string('lot_number')->index();
            $table->decimal('initial_quantity', 15, 4);
            $table->decimal('remaining_quantity', 15, 4);
            $table->decimal('unit_cost', 15, 4);
            
            // Fechas importantes
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('received_date');
            
            // Origen
            $table->foreignUuid('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->string('purchase_order_number')->nullable();
            
            // Estado
            $table->enum('status', ['active', 'depleted', 'expired', 'quarantine'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['tenant_id', 'product_id', 'status']);
            $table->index(['tenant_id', 'lot_number']);
            $table->index(['expiry_date']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_lots');
    }
};
