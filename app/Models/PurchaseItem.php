<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'jewelry_product_id',
        'inventory_item_id',
        'serial_no',
        'gross_weight',
        'stone_weight',
        'net_weight',
        'cost_price',
        'making_charge',
        'stone_charge',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'gross_weight' => 'decimal:4',
            'stone_weight' => 'decimal:4',
            'net_weight' => 'decimal:4',
            'cost_price' => 'decimal:4',
            'making_charge' => 'decimal:4',
            'stone_charge' => 'decimal:4',
            'total' => 'decimal:4',
        ];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function jewelryProduct(): BelongsTo
    {
        return $this->belongsTo(JewelryProduct::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
