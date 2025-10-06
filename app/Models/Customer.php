<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'sex',
        'city',
        'phone_number',
    ];

    protected $casts = [
        'sex' => 'string',
    ];

    /**
     * Get the invoices for the customer.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
