<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransferItem extends Model
{
    protected $fillable = [
        'stock_transfer_id',
        'inventory_item_id',
        'jewelry_product_id',
        'serial_no',
        'gross_weight',
        'net_weight',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'gross_weight' => 'decimal:4',
            'net_weight' => 'decimal:4',
        ];
    }

    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class);
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
