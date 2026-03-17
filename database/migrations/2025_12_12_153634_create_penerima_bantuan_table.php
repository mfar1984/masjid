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
        Schema::create('penerima_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_pendaftaran', 50)->unique();

            // Maklumat Peribadi
            $table->string('nama_penuh', 255);
            $table->string('no_kp', 12)->unique();
            $table->enum('jantina', ['Lelaki', 'Perempuan']);
            $table->date('tarikh_lahir');
            $table->integer('umur')->nullable();
            $table->string('bangsa', 100)->nullable();
            $table->string('agama', 100)->default('Islam');
            $table->enum('status_perkahwinan', ['Bujang', 'Berkahwin', 'Duda', 'Janda', 'Bercerai']);
            $table->string('kewarganegaraan', 100)->default('Malaysia');

            // Maklumat Hubungan
            $table->string('no_telefon', 20);
            $table->string('no_telefon_kecemasan', 20)->nullable();
            $table->string('emel', 255)->nullable();

            // Alamat Semasa
            $table->string('alamat_1', 255);
            $table->string('alamat_2', 255)->nullable();
            $table->string('poskod', 10);
            $table->string('bandar', 100);
            $table->string('negeri', 100);

            // Maklumat Keluarga
            $table->integer('bilangan_tanggungan')->default(0);
            $table->integer('bilangan_anak')->default(0);
            $table->integer('bilangan_anak_sekolah')->default(0);
            $table->string('nama_pasangan', 255)->nullable();
            $table->string('no_kp_pasangan', 12)->nullable();
            $table->string('pekerjaan_pasangan', 255)->nullable();
            $table->decimal('pendapatan_pasangan', 10, 2)->nullable();

            // Maklumat Pekerjaan & Kewangan
            $table->enum('status_pekerjaan', ['Bekerja', 'Tidak Bekerja', 'Pesara', 'OKU', 'Pelajar', 'Suri Rumah']);
            $table->string('pekerjaan', 255)->nullable();
            $table->string('majikan', 255)->nullable();
            $table->decimal('pendapatan_bulanan', 10, 2)->nullable();
            $table->decimal('pendapatan_lain', 10, 2)->nullable();
            $table->decimal('jumlah_pendapatan', 10, 2)->nullable();

            // Maklumat Perumahan
            $table->enum('jenis_kediaman', ['Rumah Sendiri', 'Rumah Sewa', 'Rumah Keluarga', 'Rumah Pangsa', 'Rumah Setinggan', 'Lain-lain']);
            $table->decimal('sewa_bulanan', 10, 2)->nullable();

            // Kategori Kebajikan
            $table->string('kategori_penerima', 255)->nullable();
            $table->enum('status_oku', ['Ya', 'Tidak'])->default('Tidak');
            $table->string('jenis_oku', 255)->nullable();
            $table->string('no_kad_oku', 50)->nullable();
            $table->enum('status_yatim', ['Ya', 'Tidak'])->default('Tidak');
            $table->enum('status_ibu_tunggal', ['Ya', 'Tidak'])->default('Tidak');
            $table->enum('status_warga_emas', ['Ya', 'Tidak'])->default('Tidak');

            // Dokumen (JSON array of file paths)
            $table->string('gambar_profil', 255)->nullable();
            $table->text('salinan_ic')->nullable();
            $table->text('salinan_ic_pasangan')->nullable();
            $table->text('sijil_lahir_anak')->nullable();
            $table->text('slip_gaji')->nullable();
            $table->text('penyata_bank')->nullable();
            $table->text('kad_oku')->nullable();
            $table->text('sijil_kematian')->nullable();
            $table->text('surat_sokongan')->nullable();
            $table->text('dokumen_lain')->nullable();

            // Status
            $table->enum('status_penerima', ['Aktif', 'Tidak Aktif', 'Tamat'])->default('Aktif');
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
        Schema::dropIfExists('penerima_bantuan');
    }
};
