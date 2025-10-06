<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sex',
        'role',
    ];

    protected $casts = [
        'sex' => 'string',
        'role' => 'string',
    ];

    /**
     * Get the invoices for the employee.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
