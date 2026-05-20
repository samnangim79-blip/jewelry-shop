<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'inventory_item_id',
        'jewelry_product_id',
        'serial_no',
        'gold_rate',
        'gross_weight',
        'stone_weight',
        'net_weight',
        'unit_price',
        'making_charge',
        'stone_charge',
        'discount',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'gold_rate' => 'decimal:4',
            'gross_weight' => 'decimal:4',
            'stone_weight' => 'decimal:4',
            'net_weight' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'making_charge' => 'decimal:4',
            'stone_charge' => 'decimal:4',
            'discount' => 'decimal:4',
            'total' => 'decimal:4',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function jewelryProduct(): BelongsTo
    {
        return $this->belongsTo(JewelryProduct::class);
    }

    public function returnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    public function priceCalculationLogs(): HasMany
    {
        return $this->hasMany(PriceCalculationLog::class);
    }
}
