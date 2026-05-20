<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'customer_id',
        'goldsmith_id',
        'order_no',
        'jewelry_type',
        'design_description',
        'metal_type_id',
        'purity_id',
        'estimated_weight',
        'estimated_price',
        'deposit_amount',
        'final_price',
        'order_date',
        'due_date',
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'due_date' => 'date',
            'estimated_weight' => 'decimal:4',
            'estimated_price' => 'decimal:4',
            'deposit_amount' => 'decimal:4',
            'final_price' => 'decimal:4',
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

    public function metalType(): BelongsTo
    {
        return $this->belongsTo(MetalType::class);
    }

    public function purity(): BelongsTo
    {
        return $this->belongsTo(Purity::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(CustomOrderPayment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CustomOrderAttachment::class);
    }
}
