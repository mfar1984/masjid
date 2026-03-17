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
        Schema::create('pembayaran_sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_pembayaran', 50)->unique();
            
            // Relations
            $table->foreignId('tempahan_fasiliti_id')->constrained('tempahan_fasiliti')->onDelete('cascade');
            $table->foreignId('senarai_fasiliti_id')->constrained('senarai_fasiliti')->onDelete('cascade');
            
            // Maklumat Pembayaran
            $table->date('tarikh_pembayaran');
            $table->decimal('jumlah_sewa', 10, 2);
            $table->decimal('jumlah_deposit', 10, 2)->nullable();
            $table->decimal('jumlah_bayaran', 10, 2);
            $table->enum('kaedah_bayaran', ['Tunai', 'Cek', 'Bank Transfer', 'Online Banking', 'E-Wallet']);
            
            // Bank Details (if applicable)
            $table->string('nama_bank', 255)->nullable();
            $table->string('no_akaun', 50)->nullable();
            $table->string('no_rujukan', 100)->nullable();
            
            // Cek Details (if applicable)
            $table->string('no_cek', 50)->nullable();
            $table->date('tarikh_cek')->nullable();
            
            // Dokumen Pembayaran
            $table->text('resit_pembayaran_path')->nullable();
            $table->text('bukti_transfer_path')->nullable();
            $table->text('salinan_cek_path')->nullable();
            
            // Deposit Return
            $table->decimal('deposit_dikembalikan', 10, 2)->nullable();
            $table->date('tarikh_kembalikan_deposit')->nullable();
            $table->text('sebab_potongan_deposit')->nullable();
            
            // Status
            $table->enum('status_pembayaran', ['Belum Bayar', 'Sudah Bayar', 'Deposit Dikembalikan', 'Dibatalkan'])->default('Belum Bayar');
            $table->text('catatan')->nullable();
            
            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('masjid_id');
            $table->index('tempahan_fasiliti_id');
            $table->index('senarai_fasiliti_id');
            $table->index('status_pembayaran');
            $table->index('tarikh_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_sewa');
    }
};
