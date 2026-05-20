<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JewelryProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'category_id',
        'metal_type_id',
        'purity_id',
        'product_code',
        'name',
        'description',
        'design_code',
        'model_no',
        'gender',
        'is_serialized',
        'has_gemstone',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_serialized' => 'boolean',
            'has_gemstone' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JewelryCategory::class, 'category_id');
    }

    public function metalType(): BelongsTo
    {
        return $this->belongsTo(MetalType::class);
    }

    public function purity(): BelongsTo
    {
        return $this->belongsTo(Purity::class);
    }

    public function gemstones(): BelongsToMany
    {
        return $this->belongsToMany(Gemstone::class, 'jewelry_product_gemstone')
            ->withPivot(['quantity', 'carat_weight', 'stone_price'])
            ->withTimestamps();
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }
}
