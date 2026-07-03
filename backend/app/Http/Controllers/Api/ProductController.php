<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::orderBy('name')->get();
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        return response()->json(Product::create($validated), 201);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validateProduct($request, true));

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }

    private function validateProduct(Request $request, bool $partial = false): array
    {
        return $request->validate([
            'name' => [$partial ? 'sometimes' : 'required', 'string', 'max:255'],
            'category' => [$partial ? 'sometimes' : 'required', 'string', 'max:100'],
            'selling_price' => [($partial ? 'sometimes' : 'required'), 'numeric', 'min:0'],
            'shelf_life_hours' => [($partial ? 'sometimes' : 'required'), 'integer', 'min:1'],
            'unit' => [$partial ? 'sometimes' : 'required', 'string', 'max:50'],
            'is_active' => [($partial ? 'sometimes' : 'required'), 'boolean'],
        ]);
    }
}
