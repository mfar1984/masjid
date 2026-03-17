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
        Schema::create('senarai_fasiliti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('kod_fasiliti', 50)->unique();
            
            // Maklumat Fasiliti
            $table->string('nama_fasiliti', 255);
            $table->enum('jenis_fasiliti', ['Dewan', 'Bilik', 'Padang', 'Tempat Letak Kereta', 'Aset', 'Lain-lain']);
            $table->string('kategori_fasiliti', 255)->nullable();
            
            // Link to Aset (if jenis = Aset)
            $table->foreignId('senarai_aset_id')->nullable()->constrained('senarai_aset')->onDelete('set null');
            
            // Kapasiti & Spesifikasi
            $table->integer('kapasiti_maksimum')->nullable();
            $table->string('luas_kawasan', 100)->nullable();
            $table->text('kemudahan')->nullable();
            $table->text('spesifikasi')->nullable();
            
            // Harga Sewa
            $table->decimal('harga_sewa_sejam', 10, 2)->nullable();
            $table->decimal('harga_sewa_sehari', 10, 2)->nullable();
            $table->decimal('harga_sewa_separuh_hari', 10, 2)->nullable();
            $table->decimal('deposit_diperlukan', 10, 2)->nullable();
            
            // Syarat & Peraturan
            $table->text('syarat_tempahan')->nullable();
            $table->text('peraturan_penggunaan')->nullable();
            $table->integer('had_minimum_tempahan')->default(1);
            $table->integer('had_maksimum_tempahan')->nullable();
            
            // Gambar & Dokumen
            $table->text('gambar_fasiliti')->nullable();
            $table->text('dokumen_peraturan')->nullable();
            
            // Status
            $table->enum('status_fasiliti', ['Tersedia', 'Tidak Tersedia', 'Dalam Penyelenggaraan'])->default('Tersedia');
            $table->text('catatan')->nullable();
            
            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('masjid_id');
            $table->index('jenis_fasiliti');
            $table->index('status_fasiliti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senarai_fasiliti');
    }
};
