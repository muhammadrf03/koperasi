<?php

namespace App\Http\Controllers;

use App\Exports\BarangExport;
use App\Models\Category;
use App\Models\Item;
use App\Support\UrlCodec;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index(Request $request, ?Category $category = null)
    {
        $categories = Category::orderBy('name')->get();
        $activeCategory = $category;

        if ($request->filled('category')) {
            $filterId = UrlCodec::decode($request->input('category'));
            if ($filterId) {
                $activeCategory = Category::find($filterId);
            }
        }

        $query = Item::with('category');

        if ($activeCategory) {
            $query->where('category_id', $activeCategory->id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $barangs = $query->latest()->paginate(15)->withQueryString();

        return view('barang.index', compact('barangs', 'categories', 'activeCategory'));
    }

    public function export(Request $request)
    {
        $categoryId = null;

        if ($request->filled('category')) {
            $categoryId = UrlCodec::decode($request->input('category'));
        }

        $export = new BarangExport($categoryId, $request->input('search'));

        return Excel::download($export, 'data-barang.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'unit.required' => 'Satuan wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
        ]);

        Item::create($request->only('name', 'category_id', 'unit', 'stock', 'price'));

        $category = Category::findOrFail($request->category_id);

        return redirect()->route('barang.show', $category->hash)
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama barang wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'unit.required' => 'Satuan wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'price.required' => 'Harga wajib diisi.',
        ]);

        $item->update($request->only('name', 'category_id', 'unit', 'stock', 'price'));

        return redirect()->route('barang.show', $item->category->hash)
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Item $item)
    {
        $category = $item->category;
        $item->delete();

        session()->flash('deleted_item', [
            'id' => $item->hash,
            'name' => $item->name,
        ]);

        return redirect()->route('barang.show', $category->hash);
    }

    public function restore(Item $item)
    {
        $item->restore();

        return redirect()->route('barang.show', $item->category->hash)
            ->with('success', "Barang '{$item->name}' berhasil dipulihkan!");
    }
}
