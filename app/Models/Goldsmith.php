<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goldsmith extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'phone',
        'address',
        'skill_type',
        'commission_rate',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function repairOrders(): HasMany
    {
        return $this->hasMany(RepairOrder::class);
    }

    public function customOrders(): HasMany
    {
        return $this->hasMany(CustomOrder::class);
    }
}
