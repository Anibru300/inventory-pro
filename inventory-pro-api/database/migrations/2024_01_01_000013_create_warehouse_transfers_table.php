<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            
            // Almacenes
            $table->foreignUuid('source_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignUuid('destination_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            
            // Información general
            $table->string('transfer_number')->unique();
            $table->date('transfer_date');
            $table->date('expected_arrival_date')->nullable();
            $table->date('actual_arrival_date')->nullable();
            
            // Estados del flujo de transferencia
            $table->enum('status', [
                'pending',           // PENDIENTE - Creada, esperando preparación
                'preparing',         // EN PREPARACIÓN - Se está recolectando
                'in_transit',        // EN TRÁNSITO - Despachada, en camino
                'received',          // RECIBIDA - Llegó al destino
                'partially_received',// PARCIALMENTE RECIBIDA - Solo parte llegó
                'cancelled',         // CANCELADA - Anulada
                'rejected'           // RECHAZADA - Destino no aceptó
            ])->default('pending');
            
            // Tracking
            $table->string('tracking_number')->nullable();
            $table->string('carrier_name')->nullable();
            $table->string('shipping_method')->nullable();
            
            // Totales
            $table->decimal('total_items', 15, 4)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->nullable();
            
            // Responsables
            $table->foreignUuid('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('sent_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignUuid('received_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Timestamps de estados
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('received_at')->nullable();
            
            // Notas
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'transfer_number']);
            $table->index(['source_warehouse_id']);
            $table->index(['destination_warehouse_id']);
            $table->index(['transfer_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_transfers');
    }
};
