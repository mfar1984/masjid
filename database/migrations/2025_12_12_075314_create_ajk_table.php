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
        Schema::create('ajk', function (Blueprint $table) {
            $table->id();
            
            // Maklumat Peribadi
            $table->string('nama');
            $table->string('no_ic', 14)->unique();
            $table->string('telefon', 15);
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('jantina', ['Lelaki', 'Perempuan', 'Tidak Dinyatakan'])->default('Tidak Dinyatakan');
            
            // Maklumat Jawatan
            $table->string('jawatan'); // Pengerusi, Naib Pengerusi, Setiausaha, Bendahari, dll
            $table->date('tarikh_lantikan');
            $table->date('tarikh_tamat')->nullable();
            $table->string('tempoh_jawatan')->nullable(); // 2 Tahun, 3 Tahun, dll
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Menunggu', 'Ditolak', 'Digantung'])->default('Menunggu');
            
            // Dokumen
            $table->string('ic_depan_path')->nullable();
            $table->string('ic_belakang_path')->nullable();
            $table->string('surat_lantikan_path')->nullable();
            
            // Multi-tenant (WAJIB)
            $table->unsignedBigInteger('masjid_id')->nullable();
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            
            // Workflow fields
            $table->unsignedBigInteger('diluluskan_oleh')->nullable();
            $table->foreign('diluluskan_oleh')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('tarikh_diluluskan')->nullable();
            $table->text('catatan_kelulusan')->nullable();
            
            // Suspend tracking
            $table->timestamp('suspended_at')->nullable();
            $table->unsignedBigInteger('suspended_by')->nullable();
            $table->foreign('suspended_by')->references('id')->on('users')->onDelete('set null');
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajk');
    }
};
