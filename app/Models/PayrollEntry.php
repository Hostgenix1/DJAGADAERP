<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id', 'period', 'gross_salary', 'allowances', 'deductions',
        'net_salary', 'currency_id', 'status', 'paid_on', 'notes',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_on' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}