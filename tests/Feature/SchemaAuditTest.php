<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomOrder;
use App\Models\CustomOrderAttachment;
use App\Models\CustomOrderPayment;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Gemstone;
use App\Models\GoldRate;
use App\Models\Goldsmith;
use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use App\Models\InventoryItem;
use App\Models\JewelryCategory;
use App\Models\JewelryProduct;
use App\Models\JewelryProductGemstone;
use App\Models\LoginHistory;
use App\Models\MetalType;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\Permission;
use App\Models\PriceCalculationLog;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchasePayment;
use App\Models\Purity;
use App\Models\RepairOrder;
use App\Models\RepairPayment;
use App\Models\Role;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Supplier;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit-level test that mass-assigns at least one row into every table defined
 * in `2026_05_18_000000_create_jewelry_shop_management_system_tables.php` via
 * its Eloquent model. It will fail if a model is missing a fillable column,
 * has the wrong table/cast, or breaks a foreign-key constraint declared in
 * the migration.
 */
class SchemaAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_table_in_the_master_migration_has_a_working_model(): void
    {
        $company = Company::create([
            'name' => 'Audit Co',
            'code' => 'AUDIT-1',
            'owner_name' => 'Owner',
            'phone' => '111',
            'email' => 'audit@co.local',
            'website' => 'https://audit.local',
            'address' => 'Audit St.',
            'logo' => null,
            'tax_no' => 'TAX-1',
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $branch = Branch::create([
            'company_id' => $company->id,
            'name' => 'HQ',
            'code' => 'HQ',
            'phone' => '222',
            'email' => 'hq@audit.local',
            'address' => 'Audit St.',
            'is_main' => true,
            'status' => 'active',
        ]);

        $warehouse = Warehouse::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'name' => 'Main Safe',
            'code' => 'SAFE',
            'warehouse_type' => 'safe',
            'status' => 'active',
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'name' => 'Audit User',
            'email' => 'user@audit.local',
            'phone' => '333',
            'password' => 'password',
            'user_type' => 'admin',
            'status' => 'active',
        ]);

        $branch->update(['manager_id' => $user->id]);
        $this->assertSame($user->id, $branch->fresh()->manager_id);

        $role = Role::create([
            'company_id' => $company->id,
            'name' => 'Auditor',
            'slug' => 'auditor',
            'description' => 'Read-only',
            'status' => 'active',
        ]);

        $permission = Permission::create([
            'module' => 'audit',
            'name' => 'View',
            'slug' => 'audit.view',
            'description' => 'View audit',
        ]);

        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $customer = Customer::create([
            'company_id' => $company->id,
            'customer_code' => 'CUST-1',
            'name' => 'Audit Customer',
            'phone' => '444',
            'email' => 'cust@audit.local',
            'gender' => 'female',
            'dob' => '1990-01-01',
            'address' => 'Cust St.',
            'customer_type' => 'regular',
            'opening_balance' => 0,
            'credit_limit' => 0,
            'status' => 'active',
        ]);
        $this->assertSame('1990-01-01', $customer->dob->format('Y-m-d'));

        $supplier = Supplier::create([
            'company_id' => $company->id,
            'supplier_code' => 'SUP-1',
            'name' => 'Audit Supplier',
            'phone' => '555',
            'email' => 'sup@audit.local',
            'address' => 'Sup St.',
            'supplier_type' => 'gold',
            'opening_balance' => 0,
            'status' => 'active',
        ]);

        $goldsmith = Goldsmith::create([
            'company_id' => $company->id,
            'name' => 'Audit Goldsmith',
            'phone' => '666',
            'address' => 'Smith St.',
            'skill_type' => 'making',
            'commission_rate' => 5.00,
            'status' => 'active',
        ]);

        $category = JewelryCategory::create([
            'company_id' => $company->id,
            'parent_id' => null,
            'name' => 'Rings',
            'code' => 'RING',
            'description' => 'Rings category',
            'status' => 'active',
        ]);

        $metalType = MetalType::create([
            'company_id' => $company->id,
            'name' => 'Gold',
            'code' => 'AU',
            'status' => 'active',
        ]);

        $purity = Purity::create([
            'company_id' => $company->id,
            'metal_type_id' => $metalType->id,
            'name' => '24K',
            'purity_percent' => 99.9,
            'code' => '24K',
            'status' => 'active',
        ]);

        $gemstone = Gemstone::create([
            'company_id' => $company->id,
            'name' => 'Ruby',
            'code' => 'RBY',
            'color' => 'red',
            'clarity' => 'VS1',
            'cut' => 'oval',
            'carat_weight' => 1.25,
            'status' => 'active',
        ]);

        $product = JewelryProduct::create([
            'company_id' => $company->id,
            'category_id' => $category->id,
            'metal_type_id' => $metalType->id,
            'purity_id' => $purity->id,
            'product_code' => 'PROD-1',
            'name' => 'Ruby Ring',
            'description' => 'Test ring',
            'design_code' => 'D-1',
            'model_no' => 'M-1',
            'gender' => 'women',
            'is_serialized' => true,
            'has_gemstone' => true,
            'status' => 'active',
        ]);

        JewelryProductGemstone::create([
            'jewelry_product_id' => $product->id,
            'gemstone_id' => $gemstone->id,
            'quantity' => 1,
            'carat_weight' => 1.25,
            'stone_price' => 500,
        ]);
        $this->assertCount(1, $product->gemstones()->get());

        $inventoryItem = InventoryItem::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'warehouse_id' => $warehouse->id,
            'jewelry_product_id' => $product->id,
            'serial_no' => 'SN-1',
            'barcode' => 'BC-1',
            'gross_weight' => 10.5,
            'stone_weight' => 0.5,
            'net_weight' => 10.0,
            'wastage_percent' => 1.5,
            'making_charge' => 50,
            'stone_charge' => 100,
            'cost_price' => 1000,
            'sale_price' => 1500,
            'item_condition' => 'new',
            'stock_status' => 'available',
            'status' => 'active',
        ]);

        StockMovement::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'warehouse_id' => $warehouse->id,
            'inventory_item_id' => $inventoryItem->id,
            'jewelry_product_id' => $product->id,
            'movement_type' => 'purchase',
            'reference_type' => 'purchases',
            'reference_id' => 1,
            'qty_in' => 1,
            'qty_out' => 0,
            'weight_in' => 10,
            'weight_out' => 0,
            'created_by' => $user->id,
        ]);

        $adjustment = StockAdjustment::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'adjustment_no' => 'ADJ-1',
            'adjustment_date' => '2026-01-01',
            'reason' => 'Annual stock count',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        StockAdjustmentItem::create([
            'stock_adjustment_id' => $adjustment->id,
            'inventory_item_id' => $inventoryItem->id,
            'jewelry_product_id' => $product->id,
            'adjustment_type' => 'increase',
            'qty' => 1,
            'weight' => 1.5,
        ]);

        GoldRate::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'metal_type_id' => $metalType->id,
            'purity_id' => $purity->id,
            'rate_date' => '2026-01-01',
            'buy_rate_per_gram' => 100,
            'sell_rate_per_gram' => 110,
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $purchase = Purchase::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'warehouse_id' => $warehouse->id,
            'supplier_id' => $supplier->id,
            'purchase_no' => 'PO-1',
            'purchase_date' => '2026-01-02',
            'purchase_type' => 'cash',
            'subtotal' => 1000,
            'grand_total' => 1000,
            'paid_amount' => 1000,
            'payment_status' => 'paid',
            'status' => 'received',
            'created_by' => $user->id,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'jewelry_product_id' => $product->id,
            'inventory_item_id' => $inventoryItem->id,
            'serial_no' => 'SN-1',
            'gross_weight' => 10.5,
            'net_weight' => 10.0,
            'cost_price' => 1000,
            'total' => 1000,
        ]);

        PurchasePayment::create([
            'purchase_id' => $purchase->id,
            'supplier_id' => $supplier->id,
            'payment_date' => '2026-01-02',
            'method' => 'cash',
            'amount' => 1000,
            'paid_by' => $user->id,
        ]);

        $sale = Sale::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'sale_no' => 'SO-1',
            'sale_date' => '2026-01-03 10:00:00',
            'sale_type' => 'retail',
            'subtotal' => 1500,
            'grand_total' => 1500,
            'paid_amount' => 1500,
            'payment_status' => 'paid',
            'sale_status' => 'completed',
            'created_by' => $user->id,
        ]);

        $saleItem = SaleItem::create([
            'sale_id' => $sale->id,
            'inventory_item_id' => $inventoryItem->id,
            'jewelry_product_id' => $product->id,
            'serial_no' => 'SN-1',
            'gold_rate' => 110,
            'gross_weight' => 10.5,
            'net_weight' => 10.0,
            'unit_price' => 1100,
            'making_charge' => 50,
            'stone_charge' => 100,
            'discount' => 0,
            'total' => 1500,
        ]);

        SalePayment::create([
            'sale_id' => $sale->id,
            'customer_id' => $customer->id,
            'payment_date' => '2026-01-03 10:05:00',
            'method' => 'cash',
            'amount' => 1500,
            'received_by' => $user->id,
        ]);

        PriceCalculationLog::create([
            'sale_item_id' => $saleItem->id,
            'inventory_item_id' => $inventoryItem->id,
            'gold_rate' => 110,
            'net_weight' => 10,
            'gold_value' => 1100,
            'making_charge' => 50,
            'stone_charge' => 100,
            'discount' => 0,
            'final_price' => 1250,
        ]);

        $saleReturn = SaleReturn::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'sale_id' => $sale->id,
            'customer_id' => $customer->id,
            'return_no' => 'RET-1',
            'return_date' => '2026-01-04 09:00:00',
            'return_type' => 'refund',
            'total_refund' => 1500,
            'reason' => 'Wrong size',
            'status' => 'approved',
            'approved_by' => $user->id,
        ]);

        SaleReturnItem::create([
            'sale_return_id' => $saleReturn->id,
            'sale_item_id' => $saleItem->id,
            'inventory_item_id' => $inventoryItem->id,
            'return_amount' => 1500,
            'condition_after_return' => 'good',
        ]);

        $plan = InstallmentPlan::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'sale_id' => $sale->id,
            'plan_no' => 'PLN-1',
            'total_amount' => 1500,
            'down_payment' => 500,
            'remaining_amount' => 1000,
            'number_of_installments' => 2,
            'start_date' => '2026-02-01',
            'status' => 'active',
        ]);

        InstallmentSchedule::create([
            'installment_plan_id' => $plan->id,
            'installment_no' => 1,
            'due_date' => '2026-03-01',
            'amount_due' => 500,
            'amount_paid' => 0,
            'status' => 'unpaid',
        ]);

        $repair = RepairOrder::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'goldsmith_id' => $goldsmith->id,
            'inventory_item_id' => $inventoryItem->id,
            'repair_no' => 'RPR-1',
            'item_description' => 'Broken clasp',
            'received_date' => '2026-01-10',
            'estimated_cost' => 50,
            'final_cost' => 50,
            'paid_amount' => 50,
            'due_amount' => 0,
            'repair_status' => 'received',
            'payment_status' => 'paid',
        ]);

        RepairPayment::create([
            'repair_order_id' => $repair->id,
            'payment_date' => '2026-01-10',
            'method' => 'cash',
            'amount' => 50,
            'received_by' => $user->id,
        ]);

        $customOrder = CustomOrder::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'goldsmith_id' => $goldsmith->id,
            'order_no' => 'CO-1',
            'jewelry_type' => 'necklace',
            'design_description' => 'Floral pattern',
            'metal_type_id' => $metalType->id,
            'purity_id' => $purity->id,
            'estimated_weight' => 15,
            'estimated_price' => 2000,
            'deposit_amount' => 500,
            'final_price' => 2000,
            'order_date' => '2026-01-15',
            'due_date' => '2026-02-15',
            'status' => 'pending',
        ]);

        CustomOrderPayment::create([
            'custom_order_id' => $customOrder->id,
            'payment_date' => '2026-01-15',
            'method' => 'cash',
            'amount' => 500,
            'received_by' => $user->id,
        ]);

        CustomOrderAttachment::create([
            'custom_order_id' => $customOrder->id,
            'file_path' => 'attachments/design.png',
            'file_type' => 'image/png',
        ]);

        $secondBranch = Branch::create([
            'company_id' => $company->id,
            'name' => 'Other Branch',
            'code' => 'BR-2',
            'is_main' => false,
            'status' => 'active',
        ]);

        $transfer = StockTransfer::create([
            'company_id' => $company->id,
            'from_branch_id' => $branch->id,
            'to_branch_id' => $secondBranch->id,
            'transfer_no' => 'TR-1',
            'transfer_date' => '2026-01-20',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        StockTransferItem::create([
            'stock_transfer_id' => $transfer->id,
            'inventory_item_id' => $inventoryItem->id,
            'jewelry_product_id' => $product->id,
            'serial_no' => 'SN-1',
            'gross_weight' => 10.5,
            'net_weight' => 10.0,
            'status' => 'pending',
        ]);

        $expenseCategory = ExpenseCategory::create([
            'company_id' => $company->id,
            'name' => 'Utilities',
            'status' => 'active',
        ]);

        Expense::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'expense_category_id' => $expenseCategory->id,
            'expense_no' => 'EXP-1',
            'expense_date' => '2026-01-22',
            'amount' => 75,
            'payment_method' => 'cash',
            'created_by' => $user->id,
        ]);

        $cashAccount = CashAccount::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'name' => 'Cash Drawer',
            'account_type' => 'cash',
            'currency' => 'USD',
            'opening_balance' => 0,
            'status' => 'active',
        ]);

        CashTransaction::create([
            'cash_account_id' => $cashAccount->id,
            'transaction_date' => '2026-01-22 14:00:00',
            'transaction_type' => 'out',
            'source_type' => 'expenses',
            'source_id' => 1,
            'amount' => 75,
            'created_by' => $user->id,
        ]);

        $notification = Notification::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'title' => 'System notice',
            'message' => 'Database audit complete.',
            'notification_type' => 'system',
            'target_type' => 'manager',
            'send_at' => '2026-01-22 15:00:00',
            'status' => 'sent',
        ]);

        NotificationRecipient::create([
            'notification_id' => $notification->id,
            'user_id' => $user->id,
            'read_at' => '2026-01-22 15:05:00',
            'status' => 'read',
        ]);

        SystemSetting::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'setting_key' => 'default_currency',
            'setting_value' => 'USD',
            'setting_type' => 'text',
            'group_name' => 'general',
            'status' => 'active',
        ]);

        ActivityLog::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'user_id' => $user->id,
            'module' => 'sales',
            'action' => 'created',
            'description' => 'Created sale SO-1',
            'old_data' => null,
            'new_data' => ['sale_no' => 'SO-1'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'phpunit',
        ]);

        LoginHistory::create([
            'user_id' => $user->id,
            'login_at' => '2026-01-22 14:00:00',
            'logout_at' => '2026-01-22 18:00:00',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'phpunit',
            'status' => 'success',
        ]);

        $this->assertSame(1, Company::count());
        $this->assertSame(2, Branch::count());
        $this->assertSame(1, JewelryProduct::count());
        $this->assertSame(1, $purchase->items()->count());
        $this->assertSame(1, $sale->items()->count());
        $this->assertSame(1, $sale->payments()->count());
        $this->assertSame(1, $saleReturn->items()->count());
        $this->assertSame(1, $plan->schedules()->count());
        $this->assertSame(1, $repair->payments()->count());
        $this->assertSame(1, $customOrder->payments()->count());
        $this->assertSame(1, $customOrder->attachments()->count());
        $this->assertSame(1, $transfer->items()->count());
        $this->assertSame(1, $cashAccount->transactions()->count());
        $this->assertSame(1, $notification->recipients()->count());

        $this->assertEquals(['audit.view'], $user->permissions());
        $this->assertTrue($user->hasPermission('audit.view'));
        $this->assertTrue($user->hasRole('auditor'));
    }
}
