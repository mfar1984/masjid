<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_zakat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asnaf_id')->constrained('asnaf')->onDelete('cascade');
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_permohonan')->unique();
            $table->date('tarikh_permohonan');
            $table->enum('jenis_bantuan', ['Tunai', 'Barangan', 'Pendidikan', 'Perubatan', 'Kecemasan']);
            $table->enum('kategori_bantuan', ['Bulanan', 'Sekali', 'Khas']);
            $table->decimal('jumlah_dipohon', 10, 2);
            $table->text('sebab_permohonan');
            $table->string('dokumen_sokongan_path')->nullable();
            
            // Workflow
            $table->enum('status', ['Menunggu', 'Dalam Semakan', 'Diluluskan', 'Ditolak', 'Dibatalkan'])->default('Menunggu');
            $table->date('tarikh_semakan')->nullable();
            $table->foreignId('disemak_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_semakan')->nullable();
            
            // Approval (WAJIB attachment mesyuarat)
            $table->date('tarikh_kelulusan')->nullable();
            $table->foreignId('diluluskan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('jumlah_diluluskan', 10, 2)->nullable();
            $table->text('catatan_kelulusan')->nullable();
            $table->string('minit_mesyuarat_path')->nullable();
            $table->date('tarikh_mesyuarat')->nullable();
            $table->string('no_mesyuarat')->nullable();
            
            // Rejection
            $table->text('sebab_penolakan')->nullable();
            $table->date('tarikh_penolakan')->nullable();
            
            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['masjid_id', 'status']);
            $table->index(['asnaf_id', 'status']);
            $table->index('tarikh_permohonan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_zakat');
    }
};
