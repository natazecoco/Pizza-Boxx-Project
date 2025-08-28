<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_size')
            ->withPivot('price_increase');
    }

    public function crusts(): BelongsToMany
    {
        return $this->belongsToMany(Crust::class, 'size_crust_price')
            ->withPivot('price_increase');
    }

    public function toppings(): BelongsToMany
    {
        return $this->belongsToMany(Topping::class, 'size_topping_prices')
            ->withPivot('price_increase');
    }

    public function doughs(): BelongsToMany
    {
        return $this->belongsToMany(Dough::class, 'size_dough_prices')
            ->withPivot('price_increase');
    }

    public function sauces(): BelongsToMany
    {
        return $this->belongsToMany(Sauce::class, 'size_sauce_price')
            ->withPivot('price_increase');
    }
}