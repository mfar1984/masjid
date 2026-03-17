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
        Schema::create('kutipan_dana', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('masjid_id');
            $table->string('no_kutipan', 50)->unique();
            $table->date('tarikh_kutipan');
            $table->enum('jenis_kutipan', ['Kutipan Kariah', 'Derma & Sumbangan', 'Kutipan Zakat', 'Kutipan Lain-lain']);
            
            // For Kutipan Kariah
            $table->unsignedBigInteger('kariah_id')->nullable();
            $table->string('bulan_kutipan', 20)->nullable(); // e.g., "Disember 2025"
            
            // For Derma & Sumbangan
            $table->string('nama_penderma')->nullable();
            $table->string('no_telefon_penderma', 20)->nullable();
            $table->text('alamat_penderma')->nullable();
            $table->string('jenis_derma')->nullable(); // Umum, Pembinaan, Pendidikan, etc
            
            // For Kutipan Zakat
            $table->string('jenis_zakat')->nullable(); // Fitrah, Harta, Perniagaan, etc
            $table->string('nama_pembayar')->nullable();
            $table->string('no_kp_pembayar', 12)->nullable();
            
            // Common fields
            $table->unsignedBigInteger('kategori_kewangan_id');
            $table->unsignedBigInteger('akaun_bank_id');
            $table->decimal('jumlah', 15, 2);
            $table->string('kaedah_bayaran'); // Tunai, Cek, Bank Transfer, Online
            $table->string('no_rujukan', 100)->nullable();
            $table->string('no_resit', 50)->nullable();
            $table->text('tujuan')->nullable();
            $table->text('dokumen')->nullable(); // JSON array of file paths
            $table->text('catatan')->nullable();
            
            $table->unsignedBigInteger('transaksi_kewangan_id')->nullable(); // Link to main transaction
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            // Note: kariah_id foreign key will be added when kariahs table exists
            $table->foreign('kategori_kewangan_id')->references('id')->on('kategori_kewangan')->onDelete('restrict');
            $table->foreign('akaun_bank_id')->references('id')->on('akaun_bank')->onDelete('restrict');
            // Note: transaksi_kewangan_id foreign key will be added after transaksi_kewangan table is created
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kutipan_dana');
    }
};
