<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'shelf_life_hours' => 'integer',
        'is_active' => 'boolean',
    ];

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_items')
                    ->withPivot('quantity_needed', 'notes')
                    ->withTimestamps();
    }
}
