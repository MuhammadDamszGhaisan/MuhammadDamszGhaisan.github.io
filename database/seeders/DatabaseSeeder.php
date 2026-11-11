<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin / Kasir (login dengan PIN)
        User::create([
            'name' => 'Admin Kasir',
            'username' => 'admin',
            'role' => 'admin',
            'pin' => Hash::make('20090816'),
        ]);

        // Contoh pelanggan
        User::create([
            'name' => 'Budi Pelanggan',
            'username' => 'budi',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
        ]);

        $categories = [
            'Perabotan Rumah Tangga' => 'Peralatan dan perlengkapan rumah tangga',
            'Frozen Food' => 'Makanan beku siap saji',
            'Peralatan Dapur' => 'Alat masak dan dapur',
        ];

        $categoryModels = [];
        foreach ($categories as $name => $description) {
            $categoryModels[$name] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }

        $products = [
            ['Perabotan Rumah Tangga', 'Pel Lantai', 22000, 15, 'pcs'],
            ['Perabotan Rumah Tangga', 'Sapu Lantai', 25000, 50, 'pcs'],
            ['Perabotan Rumah Tangga', 'Ember Plastik 20L', 35000, 40, 'pcs'],
            ['Perabotan Rumah Tangga', 'Keset Kaki', 30000, 30, 'pcs'],
            ['Peralatan Dapur', 'Wajan Anti Lengket', 120000, 20, 'pcs'],
            ['Peralatan Dapur', 'Pisau Dapur Set', 85000, 25, 'set'],
            ['Peralatan Dapur', 'Talenan Kayu', 20000, 60, 'pcs'],
            ['Frozen Food', 'Nugget Ayam 500gr', 35000, 100, 'pack'],
            ['Frozen Food', 'Sosis Sapi 500gr', 40000, 80, 'pack'],
            ['Frozen Food', 'Kentang Goreng 1kg', 28000, 90, 'pack'],
            ['Frozen Food', 'Dimsum Ayam 250gr', 30000, 70, 'pack'],
        ];

        foreach ($products as $index => [$category, $name, $price, $stock, $unit]) {
            Product::create([
                'category_id' => $categoryModels[$category]->id,
                'name' => $name,
                'slug' => Str::slug($name).'-'.Str::random(4),
                'description' => 'Produk berkualitas '.$name.'.',
                'price' => $price,
                'stock' => $stock,
                'unit' => $unit,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
