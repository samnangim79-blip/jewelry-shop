<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'warehouse_id',
        'jewelry_product_id',
        'serial_no',
        'barcode',
        'rfid_tag',
        'gross_weight',
        'stone_weight',
        'net_weight',
        'wastage_percent',
        'making_charge',
        'stone_charge',
        'cost_price',
        'sale_price',
        'certificate_no',
        'certificate_file',
        'item_condition',
        'stock_status',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'gross_weight' => 'decimal:4',
            'stone_weight' => 'decimal:4',
            'net_weight' => 'decimal:4',
            'wastage_percent' => 'decimal:2',
            'making_charge' => 'decimal:4',
            'stone_charge' => 'decimal:4',
            'cost_price' => 'decimal:4',
            'sale_price' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function jewelryProduct(): BelongsTo
    {
        return $this->belongsTo(JewelryProduct::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
