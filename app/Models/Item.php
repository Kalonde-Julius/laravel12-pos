<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable = [
        'sales_items_id',
        'name',
        'sku',
        'price',
        'status',
    ];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function salesItems()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function salesDetail(){
        return $this->belongsTo(SaleDetails::class);
    }
}
