<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustmentItem extends Model
{
    protected $fillable = [
        'stock_adjustment_id',
        'inventory_item_id',
        'jewelry_product_id',
        'adjustment_type',
        'qty',
        'weight',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:4',
            'weight' => 'decimal:4',
        ];
    }

    public function stockAdjustment(): BelongsTo
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function jewelryProduct(): BelongsTo
    {
        return $this->belongsTo(JewelryProduct::class);
    }
}
