<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            if (DB::getDriverName() === 'sqlite') {
                $table->uuid('id')->primary();
            } else {
                $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            }
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            $table->uuid('parent_id')->nullable();
            
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('color')->default('#6366F1');
            $table->string('icon')->nullable();
            
            $table->string('path')->nullable();
            $table->integer('level')->default(0);
            
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'is_active']);
            $table->index('parent_id');
        });

        // Add foreign key for parent_id after table creation
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};