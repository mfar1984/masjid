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
        Schema::create('senarai_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_aset', 50)->unique();
            
            // Kategori & Maklumat Asas
            $table->foreignId('kategori_aset_id')->constrained('kategori_aset')->onDelete('restrict');
            $table->string('nama_aset', 255);
            $table->string('kod_aset', 50)->nullable();
            $table->string('jenis_aset', 255)->nullable();
            
            // Maklumat Pembelian
            $table->date('tarikh_perolehan');
            $table->enum('cara_perolehan', ['Pembelian', 'Derma', 'Hibah', 'Wakaf', 'Pinjaman', 'Lain-lain']);
            $table->string('pembekal', 255)->nullable();
            $table->string('no_invois', 100)->nullable();
            $table->decimal('harga_perolehan', 12, 2);
            
            // Maklumat Teknikal
            $table->string('jenama', 255)->nullable();
            $table->string('model', 255)->nullable();
            $table->string('no_siri', 255)->nullable();
            $table->string('warna', 100)->nullable();
            $table->string('saiz', 100)->nullable();
            $table->text('spesifikasi')->nullable();
            
            // Lokasi Semasa
            $table->string('lokasi_semasa', 255);
            $table->text('lokasi_terperinci')->nullable();
            
            // Warranty & Insurance
            $table->integer('tempoh_jaminan')->nullable();
            $table->date('tarikh_tamat_jaminan')->nullable();
            $table->string('no_polisi_insurans', 100)->nullable();
            $table->string('syarikat_insurans', 255)->nullable();
            $table->date('tarikh_tamat_insurans')->nullable();
            
            // Status & Kondisi
            $table->enum('status_aset', ['Aktif', 'Dalam Penyelenggaraan', 'Rosak', 'Dilupuskan', 'Hilang', 'Dipinjam', 'Disewa'])->default('Aktif');
            $table->enum('kondisi_aset', ['Baru', 'Baik', 'Sederhana', 'Teruk', 'Rosak'])->default('Baik');
            
            // Dokumen (JSON array of file paths)
            $table->text('gambar_aset')->nullable();
            $table->text('invois_path')->nullable();
            $table->text('warranty_card_path')->nullable();
            $table->text('manual_path')->nullable();
            $table->text('insurans_path')->nullable();
            $table->text('dokumen_lain')->nullable();
            
            // Catatan
            $table->text('catatan')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senarai_aset');
    }
};
