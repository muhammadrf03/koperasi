<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Item::count();

        $barangMasuk = (int) Transaction::where('type', 'in')->sum('quantity');
        $barangKeluar = (int) Transaction::where('type', 'out')->sum('quantity');

        $penggunaAktif = User::count();

        $year = now()->year;
        $years = [$year, $year - 1, $year - 2];

        $chartData = [];

        foreach ($years as $y) {
            $in = [];
            $out = [];

            for ($month = 1; $month <= 12; $month++) {
                $in[] = (int) Transaction::where('type', 'in')
                    ->whereYear('transaction_date', $y)
                    ->whereMonth('transaction_date', $month)
                    ->sum('quantity');

                $out[] = (int) Transaction::where('type', 'out')
                    ->whereYear('transaction_date', $y)
                    ->whereMonth('transaction_date', $month)
                    ->sum('quantity');
            }

            $chartData[$y] = ['in' => $in, 'out' => $out];
        }

        $monthlyIn = $chartData[$year]['in'];
        $monthlyOut = $chartData[$year]['out'];

        $kategoriLabels = [];
        $kategoriData = [];

        foreach (Category::with('items')->orderBy('name')->get() as $category) {
            $kategoriLabels[] = $category->name;
            $kategoriData[] = (int) $category->items->sum('stock');
        }

        $kategoriTransaksiLabels = [];
        $kategoriTransaksiData = [];

        $transaksiPerKategori = Transaction::query()
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->selectRaw('categories.name as category_name, SUM(transactions.quantity) as total_volume')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_volume')
            ->get();

        foreach ($transaksiPerKategori as $row) {
            $kategoriTransaksiLabels[] = $row->category_name;
            $kategoriTransaksiData[] = (int) $row->total_volume;
        }

        $stokAman = Item::where('stock', '>', 5)->count();
        $stokKritis = Item::where('stock', '<=', 5)->count();

        return view('dashboard', compact(
            'totalBarang',
            'barangMasuk',
            'barangKeluar',
            'penggunaAktif',
            'year',
            'chartData',
            'monthlyIn',
            'monthlyOut',
            'kategoriLabels',
            'kategoriData',
            'kategoriTransaksiLabels',
            'kategoriTransaksiData',
            'stokAman',
            'stokKritis'
        ));
    }
}
