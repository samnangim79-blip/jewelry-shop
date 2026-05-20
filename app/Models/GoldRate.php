<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoldRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'metal_type_id',
        'purity_id',
        'rate_date',
        'buy_rate_per_gram',
        'sell_rate_per_gram',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rate_date' => 'date',
            'buy_rate_per_gram' => 'decimal:4',
            'sell_rate_per_gram' => 'decimal:4',
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

    public function metalType(): BelongsTo
    {
        return $this->belongsTo(MetalType::class);
    }

    public function purity(): BelongsTo
    {
        return $this->belongsTo(Purity::class);
    }
}
