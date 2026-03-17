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
        Schema::create('perbelanjaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('masjid_id');
            $table->string('no_perbelanjaan', 50)->unique();
            $table->date('tarikh_perbelanjaan');
            $table->enum('jenis_perbelanjaan', ['Utiliti & Bil', 'Penyelenggaraan', 'Gaji & Elaun', 'Perbelanjaan Lain']);
            
            // For Utiliti & Bil
            $table->string('jenis_bil')->nullable(); // Elektrik, Air, Telefon, Internet, etc
            $table->string('no_bil', 100)->nullable();
            $table->string('bacaan_meter_lama', 50)->nullable();
            $table->string('bacaan_meter_baru', 50)->nullable();
            $table->date('tarikh_akhir')->nullable();
            
            // For Penyelenggaraan
            $table->string('jenis_penyelenggaraan')->nullable(); // Bangunan, Peralatan, Landskap, etc
            $table->string('kontraktor')->nullable();
            $table->string('no_telefon_kontraktor', 20)->nullable();
            $table->text('kerja_dilakukan')->nullable();
            
            // For Gaji & Elaun
            $table->string('nama_kakitangan')->nullable();
            $table->string('jawatan')->nullable();
            $table->decimal('gaji_pokok', 10, 2)->nullable();
            $table->decimal('elaun', 10, 2)->nullable();
            $table->decimal('potongan', 10, 2)->nullable();
            
            // Common fields
            $table->unsignedBigInteger('kategori_kewangan_id');
            $table->unsignedBigInteger('akaun_bank_id');
            $table->decimal('jumlah', 15, 2);
            $table->string('kaedah_bayaran'); // Tunai, Cek, Bank Transfer, Online
            $table->string('no_rujukan', 100)->nullable();
            $table->string('pembekal_vendor')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('dokumen')->nullable(); // JSON array of file paths (bills, invoices, receipts)
            $table->text('catatan')->nullable();
            
            $table->enum('status_kelulusan', ['Pending', 'Diluluskan', 'Ditolak'])->default('Diluluskan');
            $table->unsignedBigInteger('diluluskan_oleh')->nullable();
            $table->datetime('tarikh_diluluskan')->nullable();
            
            $table->unsignedBigInteger('transaksi_kewangan_id')->nullable(); // Link to main transaction
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('kategori_kewangan_id')->references('id')->on('kategori_kewangan')->onDelete('restrict');
            $table->foreign('akaun_bank_id')->references('id')->on('akaun_bank')->onDelete('restrict');
            // Note: transaksi_kewangan_id foreign key will be added after transaksi_kewangan table is created
            $table->foreign('diluluskan_oleh')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbelanjaan');
    }
};
