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
        Schema::create('pembayaran_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_pembayaran', 50)->unique();

            // Relations
            $table->foreignId('permohonan_bantuan_id')->constrained('permohonan_bantuan')->onDelete('cascade');
            $table->foreignId('penerima_bantuan_id')->constrained('penerima_bantuan')->onDelete('cascade');
            $table->foreignId('program_kebajikan_id')->constrained('program_kebajikan')->onDelete('cascade');

            // Maklumat Pembayaran
            $table->date('tarikh_pembayaran');
            $table->decimal('jumlah_bayaran', 10, 2);
            $table->enum('kaedah_bayaran', ['Tunai', 'Cek', 'Bank Transfer', 'Barangan', 'Baucar']);

            // Bank Details
            $table->string('nama_bank', 255)->nullable();
            $table->string('no_akaun', 50)->nullable();
            $table->string('no_rujukan', 100)->nullable();

            // Cek Details
            $table->string('no_cek', 50)->nullable();
            $table->date('tarikh_cek')->nullable();

            // Barangan Details
            $table->text('senarai_barangan')->nullable();
            $table->decimal('nilai_barangan', 10, 2)->nullable();

            // Dokumen Pembayaran (JSON array)
            $table->text('resit_pembayaran')->nullable();
            $table->text('salinan_cek')->nullable();
            $table->text('bukti_transfer')->nullable();
            $table->text('gambar_penyerahan_1')->nullable();
            $table->text('gambar_penyerahan_2')->nullable();
            $table->text('gambar_penyerahan_3')->nullable();

            // Penerimaan
            $table->date('tarikh_diterima')->nullable();
            $table->string('diterima_oleh', 255)->nullable();
            $table->text('surat_akuan')->nullable();
            $table->text('tandatangan_digital')->nullable();

            // Status
            $table->enum('status_pembayaran', ['Belum Bayar', 'Sudah Bayar', 'Dibatalkan'])->default('Belum Bayar');
            $table->text('catatan')->nullable();

            // Approval
            $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_dibayar')->nullable();
            $table->foreignId('disahkan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_disahkan')->nullable();

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
        Schema::dropIfExists('pembayaran_bantuan');
    }
};
