<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@sistem.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
            ]
        );

        $categories = ['Inventaris', 'Buah', 'Sayur', 'Minuman'];

        $categoryIds = [];
        foreach ($categories as $name) {
            $category = Category::firstOrCreate(['name' => $name]);
            $categoryIds[$name] = $category->id;
        }

        $items = [
            ['Mesin Kasir', 'Inventaris', 2, 'Unit', 1500000],
            ['Kursi Kantor', 'Inventaris', 15, 'Unit', 250000],
            ['Jeruk', 'Buah', 100, 'KG', 15000],
            ['Apel Fuji', 'Buah', 30, 'Kg', 35000],
            ['Pisang Cavendish', 'Buah', 35, 'Kg', 18000],
            ['Masako', 'Sayur', 5, 'Pack', 10000],
            ['Wortel', 'Sayur', 25, 'Kg', 15000],
            ['Kentang', 'Sayur', 28, 'Kg', 20000],
            ['Alkohol', 'Minuman', 16, 'Pcs', 30000],
            ['Air Mineral 600ml', 'Minuman', 120, 'Botol', 4000],
            ['Teh Kotak 250ml', 'Minuman', 80, 'Pcs', 5000],
            ['Kopi Instan Sachet', 'Minuman', 150, 'Sachet', 2500],
        ];

        $itemIds = [];
        foreach ($items as [$name, $categoryName, $stock, $unit, $price]) {
            $item = Item::firstOrCreate(
                ['name' => $name],
                [
                    'category_id' => $categoryIds[$categoryName],
                    'stock' => $stock,
                    'unit' => $unit,
                    'price' => $price,
                ]
            );
            $itemIds[] = $item->id;
        }

        if (Transaction::count() === 0) {
            $transactions = [
                ['in', 20, 'Stok awal barang'],
                ['in', 15, 'Pembelian dari supplier'],
                ['out', 5, 'Penjualan tunai anggota'],
                ['in', 30, 'Pengadaan tambahan'],
                ['out', 8, 'Penjualan harian'],
                ['in', 10, 'Restok dari gudang'],
                ['out', 3, 'Penjualan anggota'],
                ['in', 25, 'Pembelian grosir'],
                ['out', 12, 'Penjualan harian'],
                ['in', 18, 'Stok tambahan'],
            ];

            foreach ($transactions as $index => [$type, $quantity, $notes]) {
                Transaction::create([
                    'item_id' => $itemIds[$index % count($itemIds)],
                    'user_id' => $superadmin->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'transaction_date' => now()->subDays(count($transactions) - $index),
                    'notes' => $notes,
                ]);
            }
        }
    }
}
