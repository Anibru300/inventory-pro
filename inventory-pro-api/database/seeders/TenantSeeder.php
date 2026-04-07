<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds for a new tenant.
     */
    public function run(string $tenantId): void
    {
        // Create default warehouse
        Warehouse::create([
            'tenant_id' => $tenantId,
            'name' => 'Almacén Principal',
            'code' => 'ALM-01',
            'is_primary' => true,
            'address' => 'Dirección principal',
            'city' => 'Ciudad',
            'state' => 'Estado',
            'country' => 'México',
        ]);

        // Create default categories
        $categories = [
            [
                'name' => 'Electrónicos',
                'color' => '#3b82f6',
                'icon' => 'DevicePhoneMobileIcon',
            ],
            [
                'name' => 'Ropa y Accesorios',
                'color' => '#ec4899',
                'icon' => 'ShoppingBagIcon',
            ],
            [
                'name' => 'Alimentos y Bebidas',
                'color' => '#10b981',
                'icon' => 'BeakerIcon',
            ],
            [
                'name' => 'Hogar y Jardín',
                'color' => '#f59e0b',
                'icon' => 'HomeIcon',
            ],
            [
                'name' => 'Deportes',
                'color' => '#8b5cf6',
                'icon' => 'TrophyIcon',
            ],
            [
                'name' => 'Libros y Papelería',
                'color' => '#ef4444',
                'icon' => 'BookOpenIcon',
            ],
            [
                'name' => 'Salud y Belleza',
                'color' => '#06b6d4',
                'icon' => 'HeartIcon',
            ],
            [
                'name' => 'Automotriz',
                'color' => '#6366f1',
                'icon' => 'TruckIcon',
            ],
            [
                'name' => 'Ferretería',
                'color' => '#84cc16',
                'icon' => 'WrenchIcon',
            ],
            [
                'name' => 'Juguetes',
                'color' => '#f97316',
                'icon' => 'FaceSmileIcon',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'tenant_id' => $tenantId,
                'name' => $category['name'],
                'slug' => \Illuminate\Support\Str::slug($category['name']),
                'color' => $category['color'],
                'icon' => $category['icon'],
                'is_active' => true,
            ]);
        }
    }
}
