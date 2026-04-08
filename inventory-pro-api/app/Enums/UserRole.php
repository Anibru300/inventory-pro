<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case OPERATOR = 'operator';
    case VIEWER = 'viewer';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::MANAGER => 'Gerente',
            self::OPERATOR => 'Operador',
            self::VIEWER => 'Visualizador',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::ADMIN => [
                'products.view', 'products.create', 'products.edit', 'products.delete',
                'movements.view', 'movements.create',
                'transfers.view', 'transfers.create', 'transfers.approve',
                'warehouses.view', 'warehouses.manage',
                'categories.view', 'categories.manage',
                'reports.view', 'reports.export',
                'users.view', 'users.manage',
                'settings.view', 'settings.manage',
                'import.execute',
            ],
            self::MANAGER => [
                'products.view', 'products.create', 'products.edit',
                'movements.view', 'movements.create',
                'transfers.view', 'transfers.create', 'transfers.approve',
                'warehouses.view', 'warehouses.manage',
                'categories.view', 'categories.manage',
                'reports.view', 'reports.export',
                'users.view',
                'settings.view',
                'import.execute',
            ],
            self::OPERATOR => [
                'products.view', 'products.create', 'products.edit',
                'movements.view', 'movements.create',
                'transfers.view', 'transfers.create',
                'warehouses.view',
                'categories.view',
                'reports.view',
            ],
            self::VIEWER => [
                'products.view',
                'movements.view',
                'transfers.view',
                'warehouses.view',
                'categories.view',
                'reports.view',
            ],
        };
    }
}
