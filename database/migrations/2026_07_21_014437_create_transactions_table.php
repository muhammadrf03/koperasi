<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke item/barang yang ditransaksikan
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            
            // Pilihan Jenis Transaksi (Barang Masuk / Barang Keluar)
            $table->enum('type', ['in', 'out']); 
            
            // Jumlah barang yang masuk/keluar
            $table->integer('quantity'); 
            
            // Tanggal Transaksi
            $table->date('transaction_date'); 
            
            // Keterangan / Catatan Tambahan (Opsional)
            $table->text('notes')->nullable(); 
            
            // Path Gambar / Foto Struk (Opsional)
            $table->string('receipt_image')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('transactions');
        Schema::enableForeignKeyConstraints();
    }
};