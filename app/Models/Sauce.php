<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sauce extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sauce');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'size_sauce_price')
            ->withPivot('price_increase');
    }
}