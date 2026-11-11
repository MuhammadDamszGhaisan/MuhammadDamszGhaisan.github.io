<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::all();

        return view('pelanggan.cart.index', compact('items'));
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('qty', 1));

        if (! $product->is_active || $product->stock < 1) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        Cart::add($product->id, min($qty, $product->stock));

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product)
    {
        $qty = (int) $request->input('qty', 1);
        $qty = min(max($qty, 0), $product->stock);

        Cart::update($product->id, $qty);

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Product $product)
    {
        Cart::remove($product->id);

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        Cart::clear();

        return redirect()->route('pelanggan.cart.index')
            ->with('success', 'Keranjang dikosongkan.');
    }
}
