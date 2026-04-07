<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_levels', function (Blueprint $table) {
            // Cantidad en tránsito (transferencias enviadas pero no recibidas)
            $table->decimal('in_transit_quantity', 15, 4)->default(0)->after('reserved_quantity');
            
            // Recalcular available_quantity para incluir in_transit
            // available = quantity - reserved - in_transit
        });
    }

    public function down(): void
    {
        Schema::table('stock_levels', function (Blueprint $table) {
            $table->dropColumn('in_transit_quantity');
        });
    }
};
