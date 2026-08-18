<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'department', 'position',
        'hire_date', 'base_salary', 'currency_id', 'is_active',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'base_salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function payrollEntries(): HasMany
    {
        return $this->hasMany(PayrollEntry::class);
    }
}