<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * READ: Tampilkan daftar kategori
     */
    public function index()
    {
        // Mengambil kategori + menghitung berapa jumlah barang di tiap kategori
        $categories = Category::withCount('items')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * CREATE Form (Opsional jika pakai halaman terpisah)
     */
    public function create()
    {
        return view ('admin.categories.create');
    }

    /**
     * CREATE: Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah ada.',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * READ: Detail Kategori & Barang di dalamnya
     */
    public function show(Category $category)
    {
        $category->load('items');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * EDIT Form (Opsional jika pakai halaman terpisah)
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * UPDATE: Perbarui data kategori
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah digunakan.',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * DELETE: Hapus kategori
     */
    public function destroy(Category $category)
    {
        // Proteksi: Cek apakah ada item yang terikat dengan kategori ini
        if ($category->items()->exists()) {
            return redirect()->back()->with('error', 'Kategori gagal dihapus karena masih memiliki barang yang terikat!');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}