<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear tenant y usuario sin usar seeder con dependencias
        $tenant = \App\Models\Tenant::create([
            'id' => 'default',
            'name' => 'Default Company',
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'tenant_id' => $tenant->id,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@inventorypro.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Crear bodega por defecto
        \App\Models\Warehouse::create([
            'tenant_id' => $tenant->id,
            'name' => 'Bodega Principal',
            'code' => 'MAIN',
            'is_active' => true,
        ]);

        // Crear categoría por defecto
        \App\Models\Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'General',
            'description' => 'Categoría general',
        ]);

        $this->command->info('✅ Default tenant, user, warehouse and category created successfully!');
    }
}
