<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'description',
        'price',
        'is_active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function toppings(): BelongsToMany
    {
        return $this->belongsToMany(Topping::class, 'product_topping')
        ->withPivot('is_default');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    public function crusts(): BelongsToMany
    {
        return $this->belongsToMany(Crust::class, 'product_crust');
    }

    public function sauces(): BelongsToMany
    {
        return $this->belongsToMany(Sauce::class, 'product_sauce')
        ->withPivot('is_default');
    }

    public function doughs(): BelongsToMany
    {
        return $this->belongsToMany(Dough::class, 'product_dough')
        ->withPivot('is_default');
    }
}