<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Relación con lotes
            $table->foreignUuid('product_lot_id')->nullable()->constrained('product_lots')->onDelete('set null')->after('product_id');
            
            // Balance después del movimiento (para reconstrucción histórica exacta)
            // Ya existe running_balance, lo renombramos a balance_after para claridad
            // y agregamos balance_before
            $table->decimal('balance_before', 15, 4)->nullable()->after('quantity');
            
            // Referencias adicionales
            $table->foreignUuid('warehouse_transfer_id')->nullable()->constrained()->onDelete('set null')->after('reference_id');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['product_lot_id']);
            $table->dropForeign(['warehouse_transfer_id']);
            $table->dropColumn(['product_lot_id', 'balance_before', 'warehouse_transfer_id']);
        });
    }
};
