<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    private const KEY = 'cart';

    public static function all(): Collection
    {
        $items = session()->get(self::KEY, []);
        if (empty($items)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($items))->get()->keyBy('id');

        return collect($items)
            ->map(function ($qty, $id) use ($products) {
                $product = $products->get($id);
                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'qty' => (int) $qty,
                    'subtotal' => $product->price * $qty,
                ];
            })
            ->filter()
            ->values();
    }

    public static function add(int $productId, int $qty = 1): void
    {
        $items = session()->get(self::KEY, []);
        $items[$productId] = ($items[$productId] ?? 0) + $qty;
        session()->put(self::KEY, $items);
    }

    public static function update(int $productId, int $qty): void
    {
        $items = session()->get(self::KEY, []);
        if ($qty <= 0) {
            unset($items[$productId]);
        } else {
            $items[$productId] = $qty;
        }
        session()->put(self::KEY, $items);
    }

    public static function remove(int $productId): void
    {
        $items = session()->get(self::KEY, []);
        unset($items[$productId]);
        session()->put(self::KEY, $items);
    }

    public static function clear(): void
    {
        session()->forget(self::KEY);
    }

    public static function total(): float
    {
        return (float) self::all()->sum('subtotal');
    }

    public static function count(): int
    {
        return (int) collect(session()->get(self::KEY, []))->sum();
    }
}
