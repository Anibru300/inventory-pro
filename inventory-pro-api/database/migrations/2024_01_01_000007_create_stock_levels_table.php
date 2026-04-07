<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_levels', function (Blueprint $table) {
            if (DB::getDriverName() === 'sqlite') {
                $table->uuid('id')->primary();
            } else {
                $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            }
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained();
            $table->foreignUuid('warehouse_id')->constrained();
            // location_id se agregará cuando exista la tabla locations
            
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            
            $table->decimal('avg_unit_cost', 15, 2)->default(0);
            
            $table->timestamp('last_movement_at')->nullable();
            $table->string('last_movement_type')->nullable();
            
            $table->timestamp('last_counted_at')->nullable();
            $table->integer('count_discrepancy')->default(0);
            
            $table->timestamps();
            
            $table->unique(['tenant_id', 'product_id', 'warehouse_id']);
            $table->index(['tenant_id', 'product_id']);
            $table->index(['tenant_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_levels');
    }
};