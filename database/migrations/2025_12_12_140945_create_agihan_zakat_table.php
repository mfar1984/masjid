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
        Schema::create('agihan_zakat', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('permohonan_zakat_id')->constrained('permohonan_zakat')->onDelete('cascade');
            $table->foreignId('asnaf_id')->constrained('asnaf')->onDelete('cascade');
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            
            // Agihan Details
            $table->string('no_agihan')->unique();
            $table->date('tarikh_agihan');
            $table->decimal('jumlah_diagihkan', 10, 2);
            $table->enum('kaedah_bayaran', ['Tunai', 'Cek', 'Bank Transfer', 'E-Wallet']);
            $table->string('no_rujukan')->nullable(); // Cek number or transfer reference
            $table->string('nama_bank')->nullable();
            $table->string('no_akaun')->nullable();
            
            // Status
            $table->enum('status', ['Belum Bayar', 'Sudah Bayar', 'Dibatalkan'])->default('Belum Bayar');
            $table->date('tarikh_bayaran')->nullable();
            
            // Documents
            $table->string('bukti_bayaran_path')->nullable();
            $table->text('catatan')->nullable();
            
            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['masjid_id', 'status']);
            $table->index(['asnaf_id', 'status']);
            $table->index('tarikh_agihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agihan_zakat');
    }
};
