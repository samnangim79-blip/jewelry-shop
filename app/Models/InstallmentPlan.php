<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallmentPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'customer_id',
        'sale_id',
        'plan_no',
        'total_amount',
        'down_payment',
        'remaining_amount',
        'number_of_installments',
        'start_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'total_amount' => 'decimal:4',
            'down_payment' => 'decimal:4',
            'remaining_amount' => 'decimal:4',
            'number_of_installments' => 'integer',
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

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(InstallmentSchedule::class);
    }
}
