<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_kewangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('masjid_id');
            $table->string('no_transaksi', 50)->unique();
            $table->date('tarikh_transaksi');
            $table->enum('jenis_transaksi', ['Pendapatan', 'Perbelanjaan']);
            $table->unsignedBigInteger('kategori_kewangan_id');
            $table->unsignedBigInteger('akaun_bank_id');
            $table->decimal('jumlah', 15, 2);
            $table->string('kaedah_bayaran')->nullable(); // Tunai, Cek, Bank Transfer, etc
            $table->string('no_rujukan', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('dokumen')->nullable(); // JSON array of file paths
            
            // Polymorphic relation for linking to other modules
            $table->unsignedBigInteger('rujukan_id')->nullable();
            $table->string('rujukan_type')->nullable(); // AgihanZakat, PembayaranBantuan, etc
            
            $table->enum('status', ['Selesai', 'Pending', 'Dibatalkan'])->default('Selesai');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('kategori_kewangan_id')->references('id')->on('kategori_kewangan')->onDelete('restrict');
            $table->foreign('akaun_bank_id')->references('id')->on('akaun_bank')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['rujukan_id', 'rujukan_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_kewangan');
    }
};
