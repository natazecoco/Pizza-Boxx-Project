<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Crust extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_crust');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'size_crust_price')
            ->withPivot('price_increase');
    }
}