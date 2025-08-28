<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductTopping extends Pivot
{
    use HasFactory;

    protected $table = 'product_topping';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'topping_id',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}