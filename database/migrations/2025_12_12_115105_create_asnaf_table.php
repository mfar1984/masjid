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
        Schema::create('asnaf', function (Blueprint $table) {
            $table->id();

            // Maklumat Peribadi
            $table->string('nama');
            $table->string('no_ic', 14)->unique();
            $table->string('jantina');
            $table->string('bangsa')->nullable();
            $table->string('agama')->default('Islam');
            $table->string('status_perkahwinan')->nullable();
            $table->string('telefon');
            $table->string('telefon_alternatif')->nullable();
            $table->string('email')->nullable();

            // Maklumat Alamat IC
            $table->text('alamat_ic');
            $table->string('poskod_ic', 5);
            $table->string('bandar_ic');
            $table->string('negeri_ic');

            // Alamat Surat Menyurat
            $table->text('alamat_surat')->nullable();
            $table->string('poskod_surat', 5)->nullable();
            $table->string('bandar_surat')->nullable();
            $table->string('negeri_surat')->nullable();

            // Alamat Kediaman Semasa
            $table->text('alamat_kediaman');
            $table->string('poskod_kediaman', 5);
            $table->string('bandar_kediaman');
            $table->string('negeri_kediaman');
            $table->string('status_kediaman'); // Rumah Sendiri, Sewa, Menumpang, Rumah Kerajaan

            // Maklumat Waris
            $table->string('nama_waris');
            $table->string('hubungan_waris');
            $table->string('no_ic_waris', 14);
            $table->string('telefon_waris');
            $table->text('alamat_waris')->nullable();

            // Kategori Asnaf
            $table->string('kategori_asnaf'); // Fakir, Miskin, Amil, Muallaf, Riqab, Gharimin, Fisabilillah, Ibnu Sabil
            $table->text('sebab_permohonan');

            // Maklumat Pekerjaan & Pendapatan
            $table->string('status_pekerjaan');
            $table->string('nama_majikan')->nullable();
            $table->string('jawatan')->nullable();
            $table->decimal('pendapatan_bulanan', 10, 2)->default(0);
            $table->decimal('pendapatan_pasangan', 10, 2)->default(0);
            $table->decimal('pendapatan_lain', 10, 2)->default(0);
            $table->string('sumber_pendapatan_lain')->nullable();

            // Maklumat Tanggungan
            $table->integer('bilangan_tanggungan')->default(0);
            $table->decimal('jumlah_perbelanjaan', 10, 2)->default(0);

            // Maklumat Hutang
            $table->boolean('ada_hutang')->default(false);
            $table->decimal('jumlah_hutang', 10, 2)->default(0);
            $table->decimal('bayaran_hutang_bulanan', 10, 2)->default(0);
            $table->text('sebab_berhutang')->nullable();

            // Maklumat Kesihatan
            $table->string('status_kesihatan')->default('Sihat');
            $table->string('jenis_penyakit')->nullable();
            $table->decimal('kos_perubatan_bulanan', 10, 2)->default(0);

            // Maklumat Aset
            $table->string('pemilikan_rumah')->default('Tiada');
            $table->string('pemilikan_kenderaan')->default('Tiada');
            $table->decimal('simpanan_bank', 10, 2)->default(0);

            // Dokumen
            $table->string('ic_depan_path')->nullable();
            $table->string('ic_belakang_path')->nullable();
            $table->string('ic_waris_path')->nullable();
            $table->string('slip_gaji_path')->nullable();
            $table->string('penyata_bank_path')->nullable();
            $table->string('bil_utiliti_path')->nullable();
            $table->string('surat_sokongan_path')->nullable();

            // Workflow & Status
            $table->string('status')->default('Menunggu'); // Menunggu, Dalam Semakan, Diluluskan, Ditolak, Digantung
            $table->text('catatan_kelulusan')->nullable();
            $table->unsignedBigInteger('diluluskan_oleh')->nullable();
            $table->timestamp('tarikh_diluluskan')->nullable();
            $table->decimal('jumlah_diluluskan', 10, 2)->default(0);

            // Multi-tenant
            $table->unsignedBigInteger('masjid_id')->nullable();
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('masjid_id');
            $table->index('status');
            $table->index('kategori_asnaf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asnaf');
    }
};
