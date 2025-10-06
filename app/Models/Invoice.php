<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'status',
        'employee_id',
        'customer_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Get the employee that owns the invoice.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the customer that owns the invoice.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the products for the invoice.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'invoice_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * Calculate the total amount of the invoice.
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->subtotal;
        });
    }
}
