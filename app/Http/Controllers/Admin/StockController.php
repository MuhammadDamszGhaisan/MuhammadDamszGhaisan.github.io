<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('stock');

        if ($search = $request->query('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->paginate(10)->withQueryString();

        return view('admin.stocks.index', compact('products'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($data);

        return back()->with('success', 'Harga & stok '.$product->name.' berhasil diperbarui.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.price' => ['required', 'numeric', 'min:0'],
            'products.*.stock' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data['products'] as $row) {
            Product::where('id', $row['id'])->update([
                'price' => $row['price'],
                'stock' => $row['stock'],
            ]);
        }

        return back()->with('success', 'Perubahan stok & harga berhasil disimpan.');
    }
}
