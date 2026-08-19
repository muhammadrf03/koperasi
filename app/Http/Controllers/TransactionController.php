<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['item.category', 'user']);

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('item', function ($item) use ($search) {
                    $item->where('name', 'like', "%{$search}%")
                         ->orWhere('notes', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('transaction_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('transaction_date', '<=', $to);
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(15)->withQueryString();

        $items = Item::orderBy('name')->get();

        $totalTransaksi = Transaction::count();
        $totalMasuk = (int) Transaction::where('type', 'in')->sum('quantity');
        $totalKeluar = (int) Transaction::where('type', 'out')->sum('quantity');
        $bulanIni = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->count();

        return view('transaction.index', compact(
            'transactions',
            'items',
            'totalTransaksi',
            'totalMasuk',
            'totalKeluar',
            'bulanIni'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id'          => 'required|exists:items,id',
            'type'             => 'required|in:in,out',
            'quantity'         => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:500',
            'receipt_image'    => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ], [
            'item_id.required'       => 'Barang wajib dipilih.',
            'item_id.exists'         => 'Barang tidak valid.',
            'type.required'          => 'Jenis transaksi wajib dipilih.',
            'type.in'                => 'Jenis transaksi tidak valid.',
            'quantity.required'      => 'Jumlah wajib diisi.',
            'quantity.min'           => 'Jumlah minimal 1.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date'  => 'Tanggal transaksi tidak valid.',
            'receipt_image.required' => 'Bukti resi/nota wajib diunggah.',
            'receipt_image.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'receipt_image.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->type === 'out' && $item->stock < $request->quantity) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => "Stok tidak mencukupi. Stok '{$item->name}' tersisa {$item->stock} {$item->unit}.",
                ]);
        }

        $receiptImage = null;
        if ($request->hasFile('receipt_image')) {
            $receiptImage = $request->file('receipt_image')->store('receipts', 'public');
        }

        Transaction::create([
            'item_id'          => $item->id,
            'user_id'          => auth()->id(),
            'type'             => $request->type,
            'quantity'         => $request->quantity,
            'transaction_date' => $request->transaction_date,
            'notes'            => $request->notes,
            'receipt_image'    => $receiptImage,
        ]);

        $item->stock += $request->type === 'in' ? $request->quantity : -$request->quantity;
        $item->save();

        $label = $request->type === 'in' ? 'masuk' : 'keluar';

        return redirect()->route('transaksi.index')
            ->with('success', "Transaksi barang {$label} berhasil dicatat!");
    }

    public function destroy(Transaction $transaction)
    {
        $item = $transaction->item;

        if ($item) {
            if ($transaction->type === 'in') {
                $item->stock = max(0, $item->stock - $transaction->quantity);
            } else {
                $item->stock += $transaction->quantity;
            }
            $item->save();
        }

        if ($transaction->receipt_image) {
            Storage::disk('public')->delete($transaction->receipt_image);
        }

        $transaction->delete();

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}
