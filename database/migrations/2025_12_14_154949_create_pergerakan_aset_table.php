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
        Schema::create('pergerakan_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_pergerakan', 50)->unique();
            
            // Relations
            $table->foreignId('senarai_aset_id')->constrained('senarai_aset')->onDelete('cascade');
            
            // Maklumat Pergerakan
            $table->dateTime('tarikh_pergerakan');
            $table->enum('jenis_pergerakan', ['Pemindahan Dalaman', 'Pemindahan Luaran', 'Pinjaman', 'Sewa', 'Penyelenggaraan', 'Pulangan']);
            
            // Lokasi Asal
            $table->string('lokasi_asal', 255);
            
            // Lokasi Destinasi (Dalaman)
            $table->string('lokasi_destinasi', 255)->nullable();
            
            // Lokasi Destinasi (Luaran) - FULL ADDRESS REQUIRED
            $table->boolean('is_lokasi_luaran')->default(false);
            $table->string('nama_tempat_luaran', 255)->nullable();
            $table->string('alamat_luaran_1', 255)->nullable();
            $table->string('alamat_luaran_2', 255)->nullable();
            $table->string('poskod_luaran', 10)->nullable();
            $table->string('bandar_luaran', 100)->nullable();
            $table->string('negeri_luaran', 100)->nullable();
            
            // Maklumat Peminjam/Penyewa (if applicable)
            $table->string('nama_peminjam', 255)->nullable();
            $table->string('no_ic_peminjam', 12)->nullable();
            $table->string('no_telefon_peminjam', 20)->nullable();
            $table->string('organisasi_peminjam', 255)->nullable();
            
            // Tempoh & Pulangan
            $table->date('tarikh_jangka_pulangan')->nullable();
            $table->dateTime('tarikh_sebenar_pulangan')->nullable();
            $table->enum('status_pulangan', ['Belum Pulang', 'Sudah Pulang', 'Lewat', 'Hilang', 'Rosak'])->default('Belum Pulang');
            
            // Kondisi
            $table->enum('kondisi_sebelum', ['Baru', 'Baik', 'Sederhana', 'Teruk', 'Rosak']);
            $table->enum('kondisi_selepas', ['Baru', 'Baik', 'Sederhana', 'Teruk', 'Rosak'])->nullable();
            
            // Dokumen
            $table->text('surat_kebenaran_path')->nullable();
            $table->text('gambar_sebelum')->nullable();
            $table->text('gambar_selepas')->nullable();
            $table->text('borang_pinjaman_path')->nullable();
            
            // Approval (for external movement)
            $table->boolean('require_approval')->default(false);
            $table->foreignId('diluluskan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_diluluskan')->nullable();
            $table->text('catatan_kelulusan')->nullable();
            
            // Catatan
            $table->text('sebab_pergerakan')->nullable();
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
        Schema::dropIfExists('pergerakan_aset');
    }
};
