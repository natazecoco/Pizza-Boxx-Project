<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Topping extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_increase',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_topping')
            ->withPivot('is_default');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'size_topping_prices')
            ->withPivot('price_increase');
    }
}