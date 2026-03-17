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
        Schema::create('program_kebajikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('kod_program', 50)->unique();
            $table->string('nama_program', 255);
            $table->enum('kategori_program', ['Pendidikan', 'Kesihatan', 'Kecemasan', 'Kebajikan Am', 'Anak Yatim', 'OKU', 'Warga Emas', 'Ibu Tunggal', 'Lain-lain']);
            $table->enum('jenis_bantuan', ['Tunai', 'Barangan', 'Perkhidmatan', 'Campuran']);
            $table->decimal('had_maksimum', 10, 2)->nullable();
            $table->decimal('had_minimum', 10, 2)->nullable();
            $table->enum('tempoh_bantuan', ['Sekali', 'Bulanan', 'Tahunan', 'Mengikut Keperluan']);
            $table->text('syarat_kelayakan')->nullable();
            $table->text('dokumen_diperlukan')->nullable();
            $table->enum('status_program', ['Aktif', 'Tidak Aktif', 'Tamat'])->default('Aktif');
            $table->date('tarikh_mula')->nullable();
            $table->date('tarikh_tamat')->nullable();
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
        Schema::dropIfExists('program_kebajikan');
    }
};
