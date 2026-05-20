<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JewelryCategory extends Model
{
    use SoftDeletes;

    protected $table = 'jewelry_categories';

    protected $fillable = [
        'company_id',
        'parent_id',
        'name',
        'code',
        'description',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function jewelryProducts(): HasMany
    {
        return $this->hasMany(JewelryProduct::class, 'category_id');
    }
}
