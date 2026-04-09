<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Índices para tabla products
        Schema::table('products', function (Blueprint $table) {
            // Índice compuesto para búsquedas por tenant + deleted_at
            if (!Schema::hasIndex('products', 'idx_products_tenant_active')) {
                $table->index(['tenant_id', 'deleted_at', 'created_at'], 'idx_products_tenant_active');
            }
            
            // Índice para búsquedas por SKU
            if (!Schema::hasIndex('products', 'idx_products_sku')) {
                $table->index(['tenant_id', 'sku'], 'idx_products_sku');
            }
            
            // Índice para búsquedas por categoría
            if (!Schema::hasIndex('products', 'idx_products_category')) {
                $table->index(['tenant_id', 'category_id'], 'idx_products_category');
            }
            
            // Índice para búsquedas por nombre (búsquedas LIKE)
            if (!Schema::hasIndex('products', 'idx_products_name')) {
                $table->index('name', 'idx_products_name');
            }
        });
        
        // Índices para tabla stock_levels
        Schema::table('stock_levels', function (Blueprint $table) {
            // Índice para consultas de stock por producto
            if (!Schema::hasIndex('stock_levels', 'idx_stock_product')) {
                $table->index(['tenant_id', 'product_id'], 'idx_stock_product');
            }
            
            // Índice para consultas de stock por almacén
            if (!Schema::hasIndex('stock_levels', 'idx_stock_warehouse')) {
                $table->index(['tenant_id', 'warehouse_id'], 'idx_stock_warehouse');
            }
        });
        
        // Índices para tabla warehouses
        Schema::table('warehouses', function (Blueprint $table) {
            // Índice para consultas de almacenes activos
            if (!Schema::hasIndex('warehouses', 'idx_warehouses_tenant_active')) {
                $table->index(['tenant_id', 'is_active', 'is_primary'], 'idx_warehouses_tenant_active');
            }
        });
        
        // Índices para tabla stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            // Índice para consultas de movimientos por producto
            if (!Schema::hasIndex('stock_movements', 'idx_movements_product')) {
                $table->index(['tenant_id', 'product_id', 'created_at'], 'idx_movements_product');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_tenant_active');
            $table->dropIndex('idx_products_sku');
            $table->dropIndex('idx_products_category');
            $table->dropIndex('idx_products_name');
        });
        
        Schema::table('stock_levels', function (Blueprint $table) {
            $table->dropIndex('idx_stock_product');
            $table->dropIndex('idx_stock_warehouse');
        });
        
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropIndex('idx_warehouses_tenant_active');
        });
        
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('idx_movements_product');
        });
    }
};
