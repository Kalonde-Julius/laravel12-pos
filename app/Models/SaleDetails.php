<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
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
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function salesItems()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function sale(Sale $record){
        return $this->belongsTo(Sale::get(['id', $record]));
    }

}
