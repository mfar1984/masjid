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
        Schema::create('permohonan_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_permohonan', 50)->unique();

            // Relations
            $table->foreignId('penerima_bantuan_id')->constrained('penerima_bantuan')->onDelete('cascade');
            $table->foreignId('program_kebajikan_id')->constrained('program_kebajikan')->onDelete('cascade');

            // Maklumat Permohonan
            $table->date('tarikh_permohonan');
            $table->enum('jenis_bantuan', ['Tunai', 'Barangan', 'Perkhidmatan', 'Campuran']);
            $table->decimal('jumlah_dipohon', 10, 2)->nullable();
            $table->text('tujuan_permohonan');
            $table->enum('keutamaan', ['Biasa', 'Sederhana', 'Tinggi', 'Kecemasan'])->default('Biasa');

            // Dokumen Sokongan (JSON array)
            $table->text('surat_permohonan')->nullable();
            $table->text('surat_hospital')->nullable();
            $table->text('sijil_kematian')->nullable();
            $table->text('resit_perbelanjaan')->nullable();
            $table->text('gambar_bukti_1')->nullable();
            $table->text('gambar_bukti_2')->nullable();
            $table->text('gambar_bukti_3')->nullable();
            $table->text('dokumen_sokongan_lain')->nullable();

            // Lawatan Rumah
            $table->date('tarikh_lawatan')->nullable();
            $table->time('masa_lawatan')->nullable();
            $table->string('pegawai_lawatan', 255)->nullable();
            $table->text('laporan_lawatan')->nullable();
            $table->text('gambar_lawatan_1')->nullable();
            $table->text('gambar_lawatan_2')->nullable();
            $table->text('gambar_lawatan_3')->nullable();
            $table->integer('skor_kelayakan')->nullable();

            // Keputusan
            $table->enum('status_permohonan', ['Baharu', 'Dalam Semakan', 'Lawatan Rumah', 'Lulus', 'Ditolak', 'Dibatalkan'])->default('Baharu');
            $table->date('tarikh_keputusan')->nullable();
            $table->decimal('jumlah_diluluskan', 10, 2)->nullable();
            $table->text('catatan_keputusan')->nullable();
            $table->text('sebab_tolak')->nullable();

            // Approval Workflow
            $table->foreignId('disemak_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_disemak')->nullable();
            $table->text('catatan_semakan')->nullable();

            $table->foreignId('diluluskan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_diluluskan')->nullable();
            $table->text('catatan_kelulusan')->nullable();

            $table->foreignId('ditolak_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_ditolak')->nullable();

            $table->foreignId('dibatalkan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_dibatalkan')->nullable();
            $table->text('sebab_batal')->nullable();

            // Additional Info
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
        Schema::dropIfExists('permohonan_bantuan');
    }
};
