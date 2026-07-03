<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class BakerySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'bread', 'description' => 'Loaves, buns, chapati, and daily bread products'],
            ['name' => 'cake', 'description' => 'Cakes, cupcakes, and sweet baked items'],
            ['name' => 'pastry', 'description' => 'Croissants, rolls, pies, and pastry products'],
            ['name' => 'bun', 'description' => 'Burger buns and soft roll products'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }

        $products = [
            ['name' => 'White Bread', 'category' => 'bread', 'selling_price' => 60, 'shelf_life_hours' => 14, 'unit' => 'loaf', 'is_active' => true],
            ['name' => 'Chocolate Cake', 'category' => 'cake', 'selling_price' => 150, 'shelf_life_hours' => 48, 'unit' => 'piece', 'is_active' => true],
            ['name' => 'Croissant', 'category' => 'pastry', 'selling_price' => 120, 'shelf_life_hours' => 12, 'unit' => 'piece', 'is_active' => true],
            ['name' => 'Burger Bun', 'category' => 'bun', 'selling_price' => 200, 'shelf_life_hours' => 8, 'unit' => 'piece', 'is_active' => false],
            ['name' => 'Brown Bread', 'category' => 'bread', 'selling_price' => 65, 'shelf_life_hours' => 24, 'unit' => 'loaf', 'is_active' => true],
            ['name' => 'Cinnamon Roll', 'category' => 'pastry', 'selling_price' => 40, 'shelf_life_hours' => 12, 'unit' => 'piece', 'is_active' => true],
            ['name' => 'Meat Pie', 'category' => 'pastry', 'selling_price' => 80, 'shelf_life_hours' => 8, 'unit' => 'piece', 'is_active' => true],
            ['name' => 'Chapati', 'category' => 'bread', 'selling_price' => 20, 'shelf_life_hours' => 8, 'unit' => 'piece', 'is_active' => true],
            ['name' => 'Sausage Roll', 'category' => 'pastry', 'selling_price' => 50, 'shelf_life_hours' => 12, 'unit' => 'piece', 'is_active' => true],
            ['name' => 'Vanilla Cupcake', 'category' => 'cake', 'selling_price' => 200, 'shelf_life_hours' => 48, 'unit' => 'piece', 'is_active' => true],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}
