<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $allPermissionIds = Permission::pluck('id');

        $admin = Role::updateOrCreate(
            ['company_id' => $company?->id, 'slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full access to all modules for the company.',
                'status' => 'active',
            ]
        );
        $admin->permissions()->sync($allPermissionIds);

        $manager = Role::updateOrCreate(
            ['company_id' => $company?->id, 'slug' => 'manager'],
            [
                'name' => 'Branch Manager',
                'description' => 'Manage branch operations and approve stock changes.',
                'status' => 'active',
            ]
        );
        $managerPermissionIds = Permission::whereIn('module', [
            'branches', 'warehouses', 'customers', 'suppliers', 'goldsmiths',
            'jewelry_categories', 'metal_types', 'purities', 'gemstones', 'jewelry_products',
            'inventory_items', 'stock_movements', 'stock_adjustments', 'stock_transfers', 'gold_rates',
            'purchases', 'sales', 'sale_returns', 'installments', 'repairs', 'custom_orders',
            'expenses', 'expense_categories', 'cash_accounts', 'cash_transactions', 'reports',
        ])->pluck('id');
        $manager->permissions()->sync($managerPermissionIds);

        $cashier = Role::updateOrCreate(
            ['company_id' => $company?->id, 'slug' => 'cashier'],
            [
                'name' => 'Cashier',
                'description' => 'Create and view sales, receive payments.',
                'status' => 'active',
            ]
        );
        $cashierPermissionIds = Permission::whereIn('slug', [
            'sales.view', 'sales.create', 'sales.pay',
            'sale_returns.view', 'sale_returns.create',
            'installments.view', 'installments.create', 'installments.pay',
            'customers.view', 'customers.create', 'customers.update',
            'jewelry_products.view', 'inventory_items.view', 'gold_rates.view',
            'cash_accounts.view', 'cash_transactions.view', 'cash_transactions.create',
        ])->pluck('id');
        $cashier->permissions()->sync($cashierPermissionIds);
    }
}
