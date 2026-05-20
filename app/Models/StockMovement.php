<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'company_id',
        'branch_id',
        'warehouse_id',
        'inventory_item_id',
        'jewelry_product_id',
        'movement_type',
        'reference_type',
        'reference_id',
        'qty_in',
        'qty_out',
        'weight_in',
        'weight_out',
        'note',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'qty_in' => 'decimal:4',
            'qty_out' => 'decimal:4',
            'weight_in' => 'decimal:4',
            'weight_out' => 'decimal:4',
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

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function jewelryProduct(): BelongsTo
    {
        return $this->belongsTo(JewelryProduct::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
