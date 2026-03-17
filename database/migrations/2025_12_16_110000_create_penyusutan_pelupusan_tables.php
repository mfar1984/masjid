<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jadual Penyusutan - Tetapan kadar susut nilai mengikut kategori
        Schema::create('jadual_penyusutan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('kategori_aset_id')->constrained('kategori_aset')->onDelete('cascade');
            $table->decimal('kadar_susut_tahunan', 5, 2)->default(10.00); // percentage
            $table->enum('kaedah_susut', ['Garis Lurus', 'Baki Berkurangan'])->default('Garis Lurus');
            $table->integer('tempoh_guna_tahun')->default(5);
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['masjid_id', 'kategori_aset_id']);
        });

        // Permohonan Pelupusan - Permohonan untuk lupus aset
        Schema::create('permohonan_pelupusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->foreignId('senarai_aset_id')->constrained('senarai_aset')->onDelete('cascade');
            $table->string('no_rujukan')->unique();
            $table->date('tarikh_permohonan');
            $table->text('sebab_pelupusan');
            $table->enum('kaedah_pelupusan', ['Jualan', 'Derma', 'Buang', 'Tukar Ganti'])->default('Buang');
            $table->decimal('nilai_pelupusan', 15, 2)->default(0);
            $table->enum('status', ['Menunggu', 'Diluluskan', 'Ditolak', 'Selesai'])->default('Menunggu');
            $table->foreignId('diluluskan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tarikh_kelulusan')->nullable();
            $table->text('catatan_kelulusan')->nullable();
            $table->date('tarikh_pelupusan')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_pelupusan');
        Schema::dropIfExists('jadual_penyusutan');
    }
};
