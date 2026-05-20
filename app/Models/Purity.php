<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purity extends Model
{
    use SoftDeletes;

    protected $table = 'purities';

    protected $fillable = [
        'company_id',
        'metal_type_id',
        'name',
        'purity_percent',
        'code',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'purity_percent' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function metalType(): BelongsTo
    {
        return $this->belongsTo(MetalType::class);
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
