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
        Schema::create('tempahan_fasiliti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_tempahan', 50)->unique();
            
            // Relations
            $table->foreignId('senarai_fasiliti_id')->constrained('senarai_fasiliti')->onDelete('cascade');
            
            // Maklumat Penyewa
            $table->string('nama_penyewa', 255);
            $table->string('no_ic_penyewa', 12);
            $table->string('no_telefon_penyewa', 20);
            $table->string('emel_penyewa', 255)->nullable();
            $table->string('alamat_penyewa_1', 255);
            $table->string('alamat_penyewa_2', 255)->nullable();
            $table->string('poskod_penyewa', 10);
            $table->string('bandar_penyewa', 100);
            $table->string('negeri_penyewa', 100);
            $table->string('organisasi_penyewa', 255)->nullable();
            
            // Maklumat Tempahan
            $table->date('tarikh_tempahan');
            $table->dateTime('tarikh_mula');
            $table->dateTime('tarikh_tamat');
            $table->integer('tempoh_sewa');
            $table->enum('unit_tempoh', ['Jam', 'Separuh Hari', 'Hari']);
            
            // Tujuan & Acara
            $table->text('tujuan_tempahan');
            $table->string('jenis_acara', 255)->nullable();
            $table->integer('bilangan_jangka_peserta')->nullable();
            
            // Harga & Bayaran
            $table->decimal('harga_sewa', 10, 2);
            $table->decimal('deposit', 10, 2)->nullable();
            $table->decimal('jumlah_bayaran', 10, 2);
            
            // Dokumen
            $table->text('surat_permohonan_path')->nullable();
            $table->text('salinan_ic_path')->nullable();
            $table->text('surat_sokongan_path')->nullable();
            $table->text('dokumen_lain')->nullable();
            
            // Status & Workflow
            $table->enum('status_tempahan', ['Baharu', 'Dalam Semakan', 'Lulus', 'Ditolak', 'Dibatalkan', 'Selesai'])->default('Baharu');
            
            // Approval - Semakan
            $table->foreignId('disemak_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_disemak')->nullable();
            $table->text('catatan_semakan')->nullable();
            
            // Approval - Kelulusan
            $table->foreignId('diluluskan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_diluluskan')->nullable();
            $table->text('catatan_kelulusan')->nullable();
            
            // Approval - Tolak
            $table->foreignId('ditolak_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_ditolak')->nullable();
            $table->text('sebab_tolak')->nullable();
            
            // Approval - Batal
            $table->foreignId('dibatalkan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_dibatalkan')->nullable();
            $table->text('sebab_batal')->nullable();
            
            // Catatan
            $table->text('catatan')->nullable();
            
            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('masjid_id');
            $table->index('senarai_fasiliti_id');
            $table->index('status_tempahan');
            $table->index('tarikh_mula');
            $table->index('tarikh_tamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempahan_fasiliti');
    }
};
