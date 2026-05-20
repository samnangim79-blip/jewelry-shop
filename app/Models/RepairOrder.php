<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepairOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'customer_id',
        'goldsmith_id',
        'inventory_item_id',
        'repair_no',
        'item_description',
        'received_date',
        'expected_return_date',
        'actual_return_date',
        'estimated_cost',
        'final_cost',
        'paid_amount',
        'due_amount',
        'repair_status',
        'payment_status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'received_date' => 'date',
            'expected_return_date' => 'date',
            'actual_return_date' => 'date',
            'estimated_cost' => 'decimal:4',
            'final_cost' => 'decimal:4',
            'paid_amount' => 'decimal:4',
            'due_amount' => 'decimal:4',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function goldsmith(): BelongsTo
    {
        return $this->belongsTo(Goldsmith::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RepairPayment::class);
    }
}
