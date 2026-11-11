<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Product::with('category')->where('is_active', true);

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->query('category')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('sort_order')->orderBy('id')->paginate(12)->withQueryString();

        return view('pelanggan.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->take(4)
            ->get();

        return view('pelanggan.products.show', compact('product', 'related'));
    }
}
