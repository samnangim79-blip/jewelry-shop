<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetalType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function purities(): HasMany
    {
        return $this->hasMany(Purity::class);
    }

    public function jewelryProducts(): HasMany
    {
        return $this->hasMany(JewelryProduct::class);
    }

    public function goldRates(): HasMany
    {
        return $this->hasMany(GoldRate::class);
    }
}
