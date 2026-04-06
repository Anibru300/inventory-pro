<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo tenant
        $tenant = Tenant::create([
            'name' => 'Empresa Demo S.A.',
            'slug' => 'empresa-demo',
            'email' => 'admin@empresademo.com',
            'phone' => '+52 55 1234 5678',
            'address' => 'Av. Principal 123, Ciudad de México',
            'plan' => 'pro',
            'status' => 'active',
            'max_users' => 10,
            'max_products' => 0, // unlimited
            'max_warehouses' => 5,
            'trial_ends_at' => now()->addDays(14),
        ]);

        // Create admin user
        $admin = User::create([
            'tenant_id' => $tenant->id,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'first_name' => 'Administrador',
            'last_name' => 'Sistema',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create regular user
        $user = User::create([
            'tenant_id' => $tenant->id,
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'first_name' => 'Usuario',
            'last_name' => 'Regular',
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create default warehouse
        $warehouse = Warehouse::create([
            'tenant_id' => $tenant->id,
            'name' => 'Almacén Principal',
            'code' => 'ALM-01',
            'address' => 'Av. Principal 123',
            'city' => 'Ciudad de México',
            'state' => 'CDMX',
            'country' => 'México',
            'is_primary' => true,
            'is_active' => true,
        ]);

        // Create categories
        $categories = [
            ['name' => 'Electrónicos', 'color' => '#6366F1', 'icon' => 'device-phone'],
            ['name' => 'Ropa', 'color' => '#EC4899', 'icon' => 'shopping-bag'],
            ['name' => 'Alimentos', 'color' => '#10B981', 'icon' => 'cake'],
            ['name' => 'Hogar', 'color' => '#F59E0B', 'icon' => 'home'],
            ['name' => 'Deportes', 'color' => '#3B82F6', 'icon' => 'sport'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'tenant_id' => $tenant->id,
                'name' => $category['name'],
                'slug' => \Illuminate\Support\Str::slug($category['name']),
                'color' => $category['color'],
                'icon' => $category['icon'],
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Tenant creado: ' . $tenant->name);
        $this->command->info('✅ Usuario admin: admin@example.com / password');
        $this->command->info('✅ Usuario regular: user@example.com / password');
        $this->command->info('✅ Almacén: ' . $warehouse->name);
        $this->command->info('✅ ' . count($categories) . ' categorías creadas');
    }
}