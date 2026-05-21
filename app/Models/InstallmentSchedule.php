<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallmentSchedule extends Model
{
    protected $fillable = [
        'installment_plan_id',
        'installment_no',
        'due_date',
        'amount_due',
        'amount_paid',
        'paid_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'installment_no' => 'integer',
            'due_date' => 'date',
            'paid_date' => 'date',
            'amount_due' => 'decimal:4',
            'amount_paid' => 'decimal:4',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(InstallmentPlan::class, 'installment_plan_id');
    }
}
