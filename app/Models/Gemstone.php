<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gemstone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'color',
        'clarity',
        'cut',
        'carat_weight',
        'certificate_no',
        'certificate_file',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'carat_weight' => 'decimal:4',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function jewelryProducts(): BelongsToMany
    {
        return $this->belongsToMany(JewelryProduct::class, 'jewelry_product_gemstone')
            ->withPivot(['quantity', 'carat_weight', 'stone_price'])
            ->withTimestamps();
    }
}
