<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function saleDetail(){
        return $this->belongsTo(SaleDetails::class);
    }
}
