<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
            
            $table->string('email');
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('avatar_url')->nullable();
            
            $table->string('role')->default('user');
            $table->json('permissions')->nullable();
            $table->json('preferences')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            
            $table->boolean('mfa_enabled')->default(false);
            $table->string('mfa_secret')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'email']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};