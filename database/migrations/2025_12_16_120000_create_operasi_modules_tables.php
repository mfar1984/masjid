<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // PROGRAM & PENDIDIKAN
        // =====================================================
        
        // Senarai Program
        Schema::create('senarai_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('nama_program');
            $table->string('kod_program')->nullable();
            $table->enum('jenis_program', ['Kuliah', 'Ceramah', 'Kursus', 'Bengkel', 'Seminar', 'Kem', 'Lain-lain'])->default('Kuliah');
            $table->enum('kategori', ['Dewasa', 'Remaja', 'Kanak-kanak', 'Wanita', 'Umum'])->default('Umum');
            $table->text('penerangan')->nullable();
            $table->string('lokasi')->nullable();
            $table->integer('kapasiti')->nullable();
            $table->decimal('yuran', 10, 2)->default(0);
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Selesai'])->default('Aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Jadual Program
        Schema::create('jadual_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('senarai_program')->onDelete('cascade');
            $table->date('tarikh');
            $table->time('masa_mula');
            $table->time('masa_tamat');
            $table->string('lokasi')->nullable();
            $table->string('penceramah')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['Dijadual', 'Sedang Berlangsung', 'Selesai', 'Batal'])->default('Dijadual');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Pendaftaran Peserta
        Schema::create('pendaftaran_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('senarai_program')->onDelete('cascade');
            $table->foreignId('jadual_id')->nullable()->constrained('jadual_program')->nullOnDelete();
            $table->string('nama_peserta');
            $table->string('no_ic')->nullable();
            $table->string('no_telefon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->date('tarikh_daftar');
            $table->enum('status_bayaran', ['Belum Bayar', 'Sudah Bayar', 'Percuma'])->default('Belum Bayar');
            $table->decimal('jumlah_bayaran', 10, 2)->default(0);
            $table->enum('status_kehadiran', ['Belum Hadir', 'Hadir', 'Tidak Hadir'])->default('Belum Hadir');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // =====================================================
        // JADUAL TUGAS - PENCERAMAH
        // =====================================================
        
        // Senarai Penceramah (Master Data)
        Schema::create('senarai_penceramah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('nama');
            $table->string('no_ic')->nullable();
            $table->string('no_telefon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('negara', ['Malaysia', 'Luar Negara'])->default('Malaysia');
            $table->string('negeri')->nullable();
            $table->string('no_sijil_tauliah')->nullable();
            $table->date('tarikh_tamat_tauliah')->nullable();
            $table->string('pihak_pengeluar')->nullable();
            $table->string('bidang_kepakaran')->nullable();
            $table->string('gambar')->nullable();
            $table->string('dokumen_sijil')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Jadual Ceramah
        Schema::create('jadual_ceramah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('penceramah_id')->constrained('senarai_penceramah')->onDelete('cascade');
            $table->date('tarikh');
            $table->time('masa_mula');
            $table->time('masa_tamat');
            $table->string('tajuk_ceramah');
            $table->enum('jenis_ceramah', ['Kuliah Subuh', 'Kuliah Maghrib', 'Kuliah Isyak', 'Ceramah Jumaat', 'Ceramah Khas', 'Tazkirah', 'Lain-lain'])->default('Kuliah Maghrib');
            $table->string('lokasi')->nullable();
            // Bahagian Bayaran
            $table->enum('jenis_bayaran', ['Sekali', 'Mingguan', 'Bulanan', 'Percuma'])->default('Sekali');
            $table->decimal('kadar_bayaran', 10, 2)->default(0);
            $table->enum('status_bayaran', ['Belum Bayar', 'Sudah Bayar'])->default('Belum Bayar');
            $table->date('tarikh_bayaran')->nullable();
            // Kos Tambahan (Optional)
            $table->decimal('kos_pengangkutan', 10, 2)->nullable();
            $table->decimal('kos_penginapan', 10, 2)->nullable();
            $table->decimal('kos_makan_minum', 10, 2)->nullable();
            $table->decimal('kos_lain', 10, 2)->nullable();
            $table->text('catatan_kos')->nullable();
            // Status
            $table->enum('status', ['Dijadual', 'Selesai', 'Batal'])->default('Dijadual');
            $table->text('catatan')->nullable();
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksi_kewangan')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // =====================================================
        // JADUAL TUGAS - IMAM & BILAL
        // =====================================================
        
        // Jadual Imam
        Schema::create('jadual_imam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('ajk_id')->nullable()->constrained('ajk')->nullOnDelete();
            $table->string('nama_imam')->nullable();
            $table->date('tarikh');
            $table->enum('waktu_solat', ['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat', 'Tarawih', 'Hari Raya'])->default('Zohor');
            $table->enum('status', ['Dijadual', 'Selesai', 'Ganti', 'Batal'])->default('Dijadual');
            $table->string('nama_ganti')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Jadual Bilal
        Schema::create('jadual_bilal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('ajk_id')->nullable()->constrained('ajk')->nullOnDelete();
            $table->string('nama_bilal')->nullable();
            $table->date('tarikh');
            $table->enum('waktu_solat', ['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat', 'Tarawih', 'Hari Raya'])->default('Zohor');
            $table->enum('status', ['Dijadual', 'Selesai', 'Ganti', 'Batal'])->default('Dijadual');
            $table->string('nama_ganti')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // =====================================================
        // KHIDMAT KOMUNITI
        // =====================================================
        
        // Urusan Jenazah
        Schema::create('urusan_jenazah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_rujukan')->unique();
            $table->string('nama_simati');
            $table->string('no_ic_simati')->nullable();
            $table->enum('jantina', ['Lelaki', 'Perempuan'])->default('Lelaki');
            $table->integer('umur')->nullable();
            $table->text('alamat_simati')->nullable();
            $table->date('tarikh_meninggal');
            $table->time('masa_meninggal')->nullable();
            $table->string('tempat_meninggal')->nullable();
            $table->string('sebab_kematian')->nullable();
            // Maklumat Waris
            $table->string('nama_waris');
            $table->string('no_telefon_waris');
            $table->string('hubungan_waris')->nullable();
            // Maklumat Pengurusan
            $table->datetime('tarikh_mandi_kafan')->nullable();
            $table->datetime('tarikh_solat_jenazah')->nullable();
            $table->string('imam_solat')->nullable();
            $table->datetime('tarikh_kebumi')->nullable();
            $table->string('lokasi_kubur')->nullable();
            $table->string('no_kubur')->nullable();
            // Kos
            $table->decimal('kos_pengurusan', 10, 2)->default(0);
            $table->enum('status_bayaran', ['Belum Bayar', 'Sudah Bayar', 'Percuma'])->default('Belum Bayar');
            // Status
            $table->enum('status', ['Dalam Proses', 'Selesai'])->default('Dalam Proses');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urusan_jenazah');
        Schema::dropIfExists('jadual_bilal');
        Schema::dropIfExists('jadual_imam');
        Schema::dropIfExists('jadual_ceramah');
        Schema::dropIfExists('senarai_penceramah');
        Schema::dropIfExists('pendaftaran_peserta');
        Schema::dropIfExists('jadual_program');
        Schema::dropIfExists('senarai_program');
    }
};
