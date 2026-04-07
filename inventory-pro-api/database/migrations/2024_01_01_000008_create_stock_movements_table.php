<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            if (DB::getDriverName() === 'sqlite') {
                $table->uuid('id')->primary();
            } else {
                $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            }
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained();
            $table->foreignUuid('warehouse_id')->constrained();
            // variant_id y location_id se agregarán cuando existan las tablas
            
            $table->string('movement_type');
            $table->integer('quantity');
            $table->decimal('unit_cost', 15, 2)->nullable();
            
            $table->string('reference_type')->nullable();
            $table->uuid('reference_id')->nullable();
            $table->string('reference_number')->nullable();
            
            $table->string('serial_number')->nullable();
            $table->string('lot_number')->nullable();
            $table->date('expiry_date')->nullable();
            
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();
            
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->integer('running_balance')->default(0);
            
            $table->timestamps();
            
            $table->index(['tenant_id', 'product_id']);
            $table->index(['tenant_id', 'warehouse_id']);
            $table->index(['tenant_id', 'created_at']);
            $table->index(['tenant_id', 'movement_type']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};