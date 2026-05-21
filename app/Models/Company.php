<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'owner_name',
        'phone',
        'email',
        'website',
        'address',
        'logo',
        'tax_no',
        'currency',
        'status',
    ];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function goldsmiths(): HasMany
    {
        return $this->hasMany(Goldsmith::class);
    }

    public function jewelryCategories(): HasMany
    {
        return $this->hasMany(JewelryCategory::class);
    }

    public function metalTypes(): HasMany
    {
        return $this->hasMany(MetalType::class);
    }

    public function purities(): HasMany
    {
        return $this->hasMany(Purity::class);
    }

    public function gemstones(): HasMany
    {
        return $this->hasMany(Gemstone::class);
    }

    public function jewelryProducts(): HasMany
    {
        return $this->hasMany(JewelryProduct::class);
    }
}
