<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceCalculationLog extends Model
{
    protected $fillable = [
        'sale_item_id',
        'inventory_item_id',
        'gold_rate',
        'net_weight',
        'gold_value',
        'making_charge',
        'stone_charge',
        'discount',
        'final_price',
    ];

    protected function casts(): array
    {
        return [
            'gold_rate' => 'decimal:4',
            'net_weight' => 'decimal:4',
            'gold_value' => 'decimal:4',
            'making_charge' => 'decimal:4',
            'stone_charge' => 'decimal:4',
            'discount' => 'decimal:4',
            'final_price' => 'decimal:4',
        ];
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
