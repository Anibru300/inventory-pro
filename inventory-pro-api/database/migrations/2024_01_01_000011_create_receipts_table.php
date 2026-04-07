<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            if (DB::getDriverName() === 'sqlite') {
                $table->uuid('id')->primary();
            } else {
                $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            }
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('stock_movement_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('warehouse_id')->constrained()->onDelete('cascade');
            
            $table->string('folio')->unique();
            $table->enum('type', ['entry', 'exit']);
            $table->integer('quantity');
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            
            // Datos del que retira (para vales de salida)
            $table->string('recipient_name')->nullable();
            $table->string('recipient_department')->nullable();
            $table->string('recipient_signature')->nullable(); // Base64 de firma
            
            // Estado
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->timestamp('printed_at')->nullable();
            
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'folio']);
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};