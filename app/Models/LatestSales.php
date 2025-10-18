<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestSales extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payment_method_id',
        'item_id',
        'sales_items_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'change',
        'status',
        'sale_date',
    ];

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function salesItems()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function sale() {
        return $this->hasMany(Sale::class);
    }
}
