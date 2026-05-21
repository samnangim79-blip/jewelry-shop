<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JewelryProductGemstone extends Model
{
    protected $table = 'jewelry_product_gemstone';

    protected $fillable = [
        'jewelry_product_id',
        'gemstone_id',
        'quantity',
        'carat_weight',
        'stone_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'carat_weight' => 'decimal:4',
            'stone_price' => 'decimal:4',
        ];
    }

    public function jewelryProduct(): BelongsTo
    {
        return $this->belongsTo(JewelryProduct::class);
    }

    public function gemstone(): BelongsTo
    {
        return $this->belongsTo(Gemstone::class);
    }
}
