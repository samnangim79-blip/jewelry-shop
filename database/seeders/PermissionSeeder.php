<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Module => list of actions. Slug is generated as `{module}.{action}`.
     */
    private array $matrix = [
        'companies' => ['view', 'create', 'update', 'delete'],
        'branches' => ['view', 'create', 'update', 'delete'],
        'warehouses' => ['view', 'create', 'update', 'delete'],
        'users' => ['view', 'create', 'update', 'delete'],
        'roles' => ['view', 'create', 'update', 'delete'],
        'permissions' => ['view', 'assign'],
        'customers' => ['view', 'create', 'update', 'delete'],
        'suppliers' => ['view', 'create', 'update', 'delete'],
        'goldsmiths' => ['view', 'create', 'update', 'delete'],
        'jewelry_categories' => ['view', 'create', 'update', 'delete'],
        'metal_types' => ['view', 'create', 'update', 'delete'],
        'purities' => ['view', 'create', 'update', 'delete'],
        'gemstones' => ['view', 'create', 'update', 'delete'],
        'jewelry_products' => ['view', 'create', 'update', 'delete'],
        'inventory_items' => ['view', 'create', 'update', 'delete'],
        'stock_movements' => ['view', 'create'],
        'stock_adjustments' => ['view', 'create', 'approve'],
        'stock_transfers' => ['view', 'create', 'approve'],
        'gold_rates' => ['view', 'create', 'update', 'delete'],
        'purchases' => ['view', 'create', 'update', 'delete', 'pay'],
        'sales' => ['view', 'create', 'update', 'delete', 'pay'],
        'sale_returns' => ['view', 'create', 'approve'],
        'installments' => ['view', 'create', 'pay'],
        'repairs' => ['view', 'create', 'update', 'pay'],
        'custom_orders' => ['view', 'create', 'update', 'pay'],
        'expenses' => ['view', 'create', 'update', 'delete'],
        'expense_categories' => ['view', 'create', 'update', 'delete'],
        'cash_accounts' => ['view', 'create', 'update', 'delete'],
        'cash_transactions' => ['view', 'create'],
        'reports' => ['view'],
        'settings' => ['view', 'update'],
    ];

    public function run(): void
    {
        foreach ($this->matrix as $module => $actions) {
            foreach ($actions as $action) {
                $slug = $module.'.'.$action;

                Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'module' => $module,
                        'name' => Str::headline($module).' - '.Str::headline($action),
                    ]
                );
            }
        }
    }
}
