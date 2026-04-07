<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            
            // Información del evento
            $table->string('event_type'); // stock_low, transfer_completed, lot_expiring, etc.
            $table->string('entity_type'); // Product, WarehouseTransfer, ProductLot, etc.
            $table->uuid('entity_id');
            
            // Datos del evento
            $table->json('payload');
            $table->json('metadata')->nullable();
            
            // Prioridad
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Estado de procesamiento
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->text('processing_error')->nullable();
            
            // Notificación
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['tenant_id', 'event_type']);
            $table->index(['tenant_id', 'processed']);
            $table->index(['entity_type', 'entity_id']);
            $table->index(['created_at']);
            $table->index(['priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_events');
    }
};
