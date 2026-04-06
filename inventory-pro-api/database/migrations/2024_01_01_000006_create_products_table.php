<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('category_id')->nullable()->constrained();
            
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            
            $table->json('attributes')->nullable();
            $table->string('unit_of_measure')->default('piece');
            $table->decimal('weight', 10, 3)->nullable();
            $table->json('dimensions')->nullable();
            
            $table->integer('stock_min')->default(0);
            $table->integer('stock_max')->nullable();
            $table->integer('reorder_point')->default(0);
            
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            
            $table->json('images')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_serialized')->default(false);
            $table->boolean('is_lotted')->default(false);
            
            $table->string('valuation_method')->nullable();
            $table->foreignUuid('preferred_supplier_id')->nullable()->constrained('suppliers');
            
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'sku']);
            $table->unique(['tenant_id', 'barcode']);
            $table->index(['tenant_id', 'category_id']);
            $table->index(['tenant_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};