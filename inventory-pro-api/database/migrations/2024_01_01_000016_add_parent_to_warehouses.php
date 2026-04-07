<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            // Jerarquía de almacenes (almacén padre)
            $table->foreignUuid('parent_id')->nullable()->constrained('warehouses')->onDelete('set null')->after('id');
            
            // Tipo de almacén
            $table->enum('type', ['central', 'regional', 'virtual', 'transit'])->default('central')->after('code');
            
            // Configuración adicional
            $table->json('settings')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'type', 'settings']);
        });
    }
};
