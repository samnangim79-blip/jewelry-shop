<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Jewelry Shop Management System - All In One Migration
|--------------------------------------------------------------------------
| Project: Jewelry Shop Management System with Multiple Branches
| Framework: Laravel 13+
|
| Notes:
| - Use this migration on a fresh database.
| - If your Laravel project already has default users/password reset/session
|   migrations, remove or disable the duplicate migrations before running this file.
| - Recommended command:
|     php artisan migrate:fresh
*/

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Company, Branch, Warehouse
        |--------------------------------------------------------------------------
        */

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('owner_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->boolean('is_main')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'code']);
            $table->index(['company_id', 'status']);
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->enum('warehouse_type', ['safe', 'display', 'storage', 'repair'])->default('storage');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['branch_id', 'code']);
            $table->index(['company_id', 'branch_id', 'status']);
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Users, Roles, Permissions
        |--------------------------------------------------------------------------
        */

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('user_type', [
                'super_admin',
                'admin',
                'manager',
                'cashier',
                'stock',
                'accountant',
                'goldsmith',
                'auditor',
            ])->default('cashier');
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'user_type']);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'slug']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['role_id', 'user_id']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['permission_id', 'role_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Customers, Suppliers, Goldsmiths
        |--------------------------------------------------------------------------
        */

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('customer_code');
            $table->string('name');
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->enum('customer_type', ['walk_in', 'regular', 'vip', 'wholesale'])->default('regular');
            $table->decimal('opening_balance', 18, 4)->default(0);
            $table->decimal('credit_limit', 18, 4)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'customer_code']);
            $table->index(['company_id', 'status']);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('supplier_code');
            $table->string('name');
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->enum('supplier_type', ['gold', 'diamond', 'gemstone', 'packaging', 'other'])->default('other');
            $table->decimal('opening_balance', 18, 4)->default(0);
            $table->decimal('credit_limit', 18, 4)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'supplier_code']);
            $table->index(['company_id', 'status']);
        });

        Schema::create('goldsmiths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable()->index();
            $table->text('address')->nullable();
            $table->enum('skill_type', ['making', 'repair', 'polishing', 'stone_setting'])->default('making');
            $table->decimal('commission_rate', 8, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Jewelry Master Data
        |--------------------------------------------------------------------------
        */

        Schema::create('jewelry_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('jewelry_categories')->nullOnDelete();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'code']);
            $table->index(['company_id', 'parent_id', 'status']);
        });

        Schema::create('metal_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'code']);
        });

        Schema::create('purities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('metal_type_id')->constrained('metal_types')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('purity_percent', 8, 4)->default(0);
            $table->string('code');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'metal_type_id', 'code']);
            $table->index(['company_id', 'metal_type_id', 'status']);
        });

        Schema::create('gemstones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('color')->nullable();
            $table->string('clarity')->nullable();
            $table->string('cut')->nullable();
            $table->decimal('carat_weight', 12, 4)->nullable();
            $table->string('certificate_no')->nullable();
            $table->string('certificate_file')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'code']);
            $table->index(['company_id', 'status']);
        });

        Schema::create('jewelry_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('jewelry_categories')->restrictOnDelete();
            $table->foreignId('metal_type_id')->constrained('metal_types')->restrictOnDelete();
            $table->foreignId('purity_id')->constrained('purities')->restrictOnDelete();
            $table->string('product_code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('design_code')->nullable();
            $table->string('model_no')->nullable();
            $table->enum('gender', ['men', 'women', 'unisex', 'kids'])->nullable();
            $table->boolean('is_serialized')->default(true);
            $table->boolean('has_gemstone')->default(false);
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'product_code']);
            $table->index(['company_id', 'category_id', 'metal_type_id', 'purity_id', 'status']);
        });

        Schema::create('jewelry_product_gemstone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jewelry_product_id')->constrained('jewelry_products')->cascadeOnDelete();
            $table->foreignId('gemstone_id')->constrained('gemstones')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('carat_weight', 12, 4)->nullable();
            $table->decimal('stone_price', 18, 4)->default(0);
            $table->timestamps();

            $table->unique(['jewelry_product_id', 'gemstone_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | 5. Inventory, Stock Movement, Adjustment
        |--------------------------------------------------------------------------
        */

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('jewelry_product_id')->constrained('jewelry_products')->restrictOnDelete();
            $table->string('serial_no')->nullable()->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('rfid_tag')->nullable()->index();
            $table->decimal('gross_weight', 12, 4)->default(0);
            $table->decimal('stone_weight', 12, 4)->default(0);
            $table->decimal('net_weight', 12, 4)->default(0);
            $table->decimal('wastage_percent', 8, 2)->default(0);
            $table->decimal('making_charge', 18, 4)->default(0);
            $table->decimal('stone_charge', 18, 4)->default(0);
            $table->decimal('cost_price', 18, 4)->default(0);
            $table->decimal('sale_price', 18, 4)->nullable();
            $table->string('certificate_no')->nullable()->index();
            $table->string('certificate_file')->nullable();
            $table->enum('item_condition', ['new', 'used', 'repaired'])->default('new');
            $table->enum('stock_status', [
                'available',
                'reserved',
                'sold',
                'transferred',
                'repair',
                'lost',
                'in_transit',
            ])->default('available');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'warehouse_id']);
            $table->index(['company_id', 'branch_id', 'stock_status']);
            $table->index(['jewelry_product_id', 'stock_status']);
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->foreignId('jewelry_product_id')->constrained('jewelry_products')->restrictOnDelete();
            $table->enum('movement_type', [
                'purchase',
                'sale',
                'return',
                'transfer_in',
                'transfer_out',
                'adjustment',
                'repair_in',
                'repair_out',
            ]);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('qty_in', 12, 4)->default(0);
            $table->decimal('qty_out', 12, 4)->default(0);
            $table->decimal('weight_in', 12, 4)->default(0);
            $table->decimal('weight_out', 12, 4)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['company_id', 'branch_id', 'movement_type']);
            $table->index(['inventory_item_id', 'movement_type']);
            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('adjustment_no')->unique();
            $table->date('adjustment_date');
            $table->string('reason');
            $table->text('note')->nullable();
            $table->enum('status', ['draft', 'approved', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'status']);
        });

        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_adjustment_id')->constrained('stock_adjustments')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->foreignId('jewelry_product_id')->constrained('jewelry_products')->restrictOnDelete();
            $table->enum('adjustment_type', ['increase', 'decrease']);
            $table->decimal('qty', 12, 4)->default(1);
            $table->decimal('weight', 12, 4)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 6. Gold Rate / Price Management
        |--------------------------------------------------------------------------
        */

        Schema::create('gold_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('metal_type_id')->constrained('metal_types')->restrictOnDelete();
            $table->foreignId('purity_id')->constrained('purities')->restrictOnDelete();
            $table->date('rate_date');
            $table->decimal('buy_rate_per_gram', 18, 4)->default(0);
            $table->decimal('sell_rate_per_gram', 18, 4)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'branch_id', 'metal_type_id', 'purity_id', 'rate_date'], 'gold_rate_unique');
            $table->index(['company_id', 'branch_id', 'rate_date']);
        });

        /*
        |--------------------------------------------------------------------------
        | 7. Purchases
        |--------------------------------------------------------------------------
        */

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->string('purchase_no')->unique();
            $table->date('purchase_date');
            $table->enum('purchase_type', ['cash', 'credit'])->default('cash');
            $table->decimal('subtotal', 18, 4)->default(0);
            $table->decimal('discount', 18, 4)->default(0);
            $table->decimal('tax', 18, 4)->default(0);
            $table->decimal('grand_total', 18, 4)->default(0);
            $table->decimal('paid_amount', 18, 4)->default(0);
            $table->decimal('due_amount', 18, 4)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('status', ['draft', 'received', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'supplier_id']);
            $table->index(['purchase_no', 'purchase_date']);
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('jewelry_product_id')->constrained('jewelry_products')->restrictOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->string('serial_no')->nullable();
            $table->decimal('gross_weight', 12, 4)->default(0);
            $table->decimal('stone_weight', 12, 4)->default(0);
            $table->decimal('net_weight', 12, 4)->default(0);
            $table->decimal('cost_price', 18, 4)->default(0);
            $table->decimal('making_charge', 18, 4)->default(0);
            $table->decimal('stone_charge', 18, 4)->default(0);
            $table->decimal('total', 18, 4)->default(0);
            $table->timestamps();

            $table->index(['purchase_id', 'jewelry_product_id']);
        });

        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->date('payment_date');
            $table->enum('method', ['cash', 'bank', 'aba', 'wing', 'cheque'])->default('cash');
            $table->decimal('amount', 18, 4)->default(0);
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['purchase_id', 'supplier_id', 'payment_date']);
        });

        /*
        |--------------------------------------------------------------------------
        | 8. POS / Sales
        |--------------------------------------------------------------------------
        */

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('sale_no')->unique();
            $table->dateTime('sale_date');
            $table->enum('sale_type', ['retail', 'wholesale', 'credit', 'exchange'])->default('retail');
            $table->decimal('subtotal', 18, 4)->default(0);
            $table->decimal('discount', 18, 4)->default(0);
            $table->decimal('tax', 18, 4)->default(0);
            $table->decimal('making_charge_total', 18, 4)->default(0);
            $table->decimal('stone_charge_total', 18, 4)->default(0);
            $table->decimal('grand_total', 18, 4)->default(0);
            $table->decimal('paid_amount', 18, 4)->default(0);
            $table->decimal('due_amount', 18, 4)->default(0);
            $table->decimal('change_amount', 18, 4)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('sale_status', ['completed', 'held', 'cancelled'])->default('completed');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'customer_id']);
            $table->index(['sale_no', 'sale_date']);
            $table->index(['payment_status', 'sale_status']);
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->restrictOnDelete();
            $table->foreignId('jewelry_product_id')->constrained('jewelry_products')->restrictOnDelete();
            $table->string('serial_no')->nullable();
            $table->decimal('gold_rate', 18, 4)->nullable();
            $table->decimal('gross_weight', 12, 4)->default(0);
            $table->decimal('stone_weight', 12, 4)->default(0);
            $table->decimal('net_weight', 12, 4)->default(0);
            $table->decimal('unit_price', 18, 4)->default(0);
            $table->decimal('making_charge', 18, 4)->default(0);
            $table->decimal('stone_charge', 18, 4)->default(0);
            $table->decimal('discount', 18, 4)->default(0);
            $table->decimal('total', 18, 4)->default(0);
            $table->timestamps();

            $table->index(['sale_id', 'inventory_item_id', 'jewelry_product_id']);
        });

        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->dateTime('payment_date');
            $table->enum('method', ['cash', 'bank', 'aba', 'wing', 'card', 'cheque'])->default('cash');
            $table->decimal('amount', 18, 4)->default(0);
            $table->string('reference_no')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['sale_id', 'customer_id', 'payment_date']);
        });

        Schema::create('price_calculation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_item_id')->nullable()->constrained('sale_items')->nullOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->decimal('gold_rate', 18, 4)->default(0);
            $table->decimal('net_weight', 12, 4)->default(0);
            $table->decimal('gold_value', 18, 4)->default(0);
            $table->decimal('making_charge', 18, 4)->default(0);
            $table->decimal('stone_charge', 18, 4)->default(0);
            $table->decimal('discount', 18, 4)->default(0);
            $table->decimal('final_price', 18, 4)->default(0);
            $table->timestamps();

            $table->index(['sale_item_id', 'inventory_item_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | 9. Sale Returns / Exchange
        |--------------------------------------------------------------------------
        */

        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('sale_id')->constrained('sales')->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('return_no')->unique();
            $table->dateTime('return_date');
            $table->enum('return_type', ['refund', 'exchange', 'store_credit'])->default('refund');
            $table->decimal('total_refund', 18, 4)->default(0);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'sale_id']);
            $table->index(['return_no', 'return_date']);
        });

        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained('sale_returns')->cascadeOnDelete();
            $table->foreignId('sale_item_id')->constrained('sale_items')->restrictOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->restrictOnDelete();
            $table->decimal('return_amount', 18, 4)->default(0);
            $table->enum('condition_after_return', ['good', 'damaged', 'repair_needed'])->default('good');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['sale_return_id', 'sale_item_id', 'inventory_item_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | 10. Installment / Layaway
        |--------------------------------------------------------------------------
        */

        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->string('plan_no')->unique();
            $table->decimal('total_amount', 18, 4)->default(0);
            $table->decimal('down_payment', 18, 4)->default(0);
            $table->decimal('remaining_amount', 18, 4)->default(0);
            $table->integer('number_of_installments')->default(1);
            $table->date('start_date');
            $table->enum('status', ['active', 'completed', 'cancelled', 'defaulted'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'customer_id']);
            $table->index(['plan_no', 'status']);
        });

        Schema::create('installment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_plan_id')->constrained('installment_plans')->cascadeOnDelete();
            $table->integer('installment_no');
            $table->date('due_date');
            $table->decimal('amount_due', 18, 4)->default(0);
            $table->decimal('amount_paid', 18, 4)->default(0);
            $table->date('paid_date')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();

            $table->unique(['installment_plan_id', 'installment_no']);
            $table->index(['due_date', 'status']);
        });

        /*
        |--------------------------------------------------------------------------
        | 11. Repair Service
        |--------------------------------------------------------------------------
        */

        Schema::create('repair_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('goldsmith_id')->nullable()->constrained('goldsmiths')->nullOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->nullOnDelete();
            $table->string('repair_no')->unique();
            $table->text('item_description');
            $table->date('received_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->decimal('estimated_cost', 18, 4)->default(0);
            $table->decimal('final_cost', 18, 4)->default(0);
            $table->decimal('paid_amount', 18, 4)->default(0);
            $table->decimal('due_amount', 18, 4)->default(0);
            $table->enum('repair_status', ['received', 'in_progress', 'completed', 'delivered', 'cancelled'])->default('received');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'customer_id']);
            $table->index(['repair_no', 'repair_status']);
        });

        Schema::create('repair_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained('repair_orders')->cascadeOnDelete();
            $table->date('payment_date');
            $table->enum('method', ['cash', 'bank', 'aba', 'wing'])->default('cash');
            $table->decimal('amount', 18, 4)->default(0);
            $table->string('reference_no')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['repair_order_id', 'payment_date']);
        });

        /*
        |--------------------------------------------------------------------------
        | 12. Custom Orders
        |--------------------------------------------------------------------------
        */

        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('goldsmith_id')->nullable()->constrained('goldsmiths')->nullOnDelete();
            $table->string('order_no')->unique();
            $table->string('jewelry_type');
            $table->text('design_description');
            $table->foreignId('metal_type_id')->constrained('metal_types')->restrictOnDelete();
            $table->foreignId('purity_id')->constrained('purities')->restrictOnDelete();
            $table->decimal('estimated_weight', 12, 4)->nullable();
            $table->decimal('estimated_price', 18, 4)->default(0);
            $table->decimal('deposit_amount', 18, 4)->default(0);
            $table->decimal('final_price', 18, 4)->default(0);
            $table->date('order_date');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delivered', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'customer_id']);
            $table->index(['order_no', 'status']);
        });

        Schema::create('custom_order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_order_id')->constrained('custom_orders')->cascadeOnDelete();
            $table->date('payment_date');
            $table->enum('method', ['cash', 'bank', 'aba', 'wing'])->default('cash');
            $table->decimal('amount', 18, 4)->default(0);
            $table->string('reference_no')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['custom_order_id', 'payment_date']);
        });

        Schema::create('custom_order_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_order_id')->constrained('custom_orders')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 13. Stock Transfer
        |--------------------------------------------------------------------------
        */

        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('from_branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('to_branch_id')->constrained('branches')->restrictOnDelete();
            $table->string('transfer_no')->unique();
            $table->date('transfer_date');
            $table->enum('status', ['draft', 'requested', 'approved', 'sent', 'received', 'cancelled'])->default('draft');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'from_branch_id', 'to_branch_id']);
            $table->index(['transfer_no', 'status']);
        });

        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_id')->constrained('stock_transfers')->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->restrictOnDelete();
            $table->foreignId('jewelry_product_id')->constrained('jewelry_products')->restrictOnDelete();
            $table->string('serial_no')->nullable();
            $table->decimal('gross_weight', 12, 4)->default(0);
            $table->decimal('net_weight', 12, 4)->default(0);
            $table->enum('status', ['pending', 'sent', 'received'])->default('pending');
            $table->timestamps();

            $table->index(['stock_transfer_id', 'inventory_item_id', 'jewelry_product_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | 14. Expense & Accounting
        |--------------------------------------------------------------------------
        */

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'name']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->restrictOnDelete();
            $table->string('expense_no')->unique();
            $table->date('expense_date');
            $table->decimal('amount', 18, 4)->default(0);
            $table->enum('payment_method', ['cash', 'bank', 'aba', 'wing'])->default('cash');
            $table->string('receipt_file')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'expense_category_id']);
            $table->index(['expense_no', 'expense_date']);
        });

        Schema::create('cash_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->enum('account_type', ['cash', 'bank', 'mobile_wallet'])->default('cash');
            $table->string('currency', 10)->default('USD');
            $table->decimal('opening_balance', 18, 4)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'branch_id', 'name']);
        });

        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_account_id')->constrained('cash_accounts')->restrictOnDelete();
            $table->dateTime('transaction_date');
            $table->enum('transaction_type', ['in', 'out']);
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->decimal('amount', 18, 4)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['cash_account_id', 'transaction_date']);
            $table->index(['source_type', 'source_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | 15. Notifications
        |--------------------------------------------------------------------------
        */

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('notification_type', ['system', 'email', 'sms', 'telegram'])->default('system');
            $table->enum('target_type', ['all', 'user', 'manager', 'cashier'])->default('all');
            $table->dateTime('send_at')->nullable();
            $table->enum('status', ['draft', 'sent', 'failed'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'branch_id', 'status']);
            $table->index(['send_at', 'notification_type']);
        });

        Schema::create('notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('read_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'read', 'failed'])->default('pending');
            $table->timestamps();

            $table->unique(['notification_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });

        /*
        |--------------------------------------------------------------------------
        | 16. System Settings
        |--------------------------------------------------------------------------
        */

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();
            $table->string('setting_key');
            $table->text('setting_value')->nullable();
            $table->string('setting_type')->default('text');
            $table->string('group_name')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'branch_id', 'setting_key'], 'system_settings_unique_key');
            $table->index(['group_name', 'status']);
        });

        /*
        |--------------------------------------------------------------------------
        | 17. Audit Log & Security
        |--------------------------------------------------------------------------
        */

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('module')->index();
            $table->string('action')->index();
            $table->text('description');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'branch_id', 'user_id']);
            $table->index(['module', 'action']);
        });

        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('login_at');
            $table->dateTime('logout_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->timestamps();

            $table->index(['user_id', 'login_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('notification_recipients');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('cash_transactions');
        Schema::dropIfExists('cash_accounts');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('custom_order_attachments');
        Schema::dropIfExists('custom_order_payments');
        Schema::dropIfExists('custom_orders');
        Schema::dropIfExists('repair_payments');
        Schema::dropIfExists('repair_orders');
        Schema::dropIfExists('installment_schedules');
        Schema::dropIfExists('installment_plans');
        Schema::dropIfExists('sale_return_items');
        Schema::dropIfExists('sale_returns');
        Schema::dropIfExists('price_calculation_logs');
        Schema::dropIfExists('sale_payments');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('purchase_payments');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('gold_rates');
        Schema::dropIfExists('stock_adjustment_items');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('jewelry_product_gemstone');
        Schema::dropIfExists('jewelry_products');
        Schema::dropIfExists('gemstones');
        Schema::dropIfExists('purities');
        Schema::dropIfExists('metal_types');
        Schema::dropIfExists('jewelry_categories');
        Schema::dropIfExists('goldsmiths');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('companies');

        Schema::enableForeignKeyConstraints();
    }
};
