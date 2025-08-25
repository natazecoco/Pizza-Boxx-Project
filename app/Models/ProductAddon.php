<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProductAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'price_increase',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}