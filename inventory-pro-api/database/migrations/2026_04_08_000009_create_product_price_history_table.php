<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_price_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('changed_by')->constrained('users')->onDelete('cascade');
            
            $table->decimal('old_cost', 12, 2);
            $table->decimal('new_cost', 12, 2);
            $table->decimal('old_price', 12, 2);
            $table->decimal('new_price', 12, 2);
            
            $table->string('reason')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_history');
    }
};
