<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollEntry extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DUE = 'due';
    public const STATUS_PAID = 'paid';

    protected $fillable = [
        'employee_id', 'period', 'base_salary', 'working_days',
        'present_days', 'absent_days', 'leave_days',
        'transport_allowance', 'bonus', 'other_allowances',
        'absence_deduction', 'other_deductions',
        'gross_salary', 'allowances', 'deductions',
        'net_salary', 'currency_id', 'status', 'paid_on', 'pay_date',
        'payslip_emailed_at', 'notes',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'present_days' => 'decimal:2',
        'absent_days' => 'decimal:2',
        'leave_days' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'absence_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_on' => 'date',
        'pay_date' => 'date',
        'payslip_emailed_at' => 'datetime',
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