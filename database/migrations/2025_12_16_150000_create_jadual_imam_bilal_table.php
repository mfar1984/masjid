<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create combined Jadual Imam & Bilal table
        Schema::create('jadual_imam_bilal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->date('tarikh');
            $table->enum('waktu_solat', ['Subuh', 'Zohor', 'Asar', 'Maghrib', 'Isyak', 'Jumaat', 'Tarawih', 'Hari Raya'])->default('Zohor');
            
            // Imam
            $table->foreignId('imam_ajk_id')->nullable()->constrained('ajk')->nullOnDelete();
            $table->string('nama_imam')->nullable();
            $table->enum('status_imam', ['Dijadual', 'Selesai', 'Ganti', 'Batal'])->default('Dijadual');
            $table->string('imam_ganti')->nullable();
            
            // Bilal
            $table->foreignId('bilal_ajk_id')->nullable()->constrained('ajk')->nullOnDelete();
            $table->string('nama_bilal')->nullable();
            $table->enum('status_bilal', ['Dijadual', 'Selesai', 'Ganti', 'Batal'])->default('Dijadual');
            $table->string('bilal_ganti')->nullable();
            
            // Auto-generate tracking
            $table->enum('jenis_jadual', ['Manual', 'Auto'])->default('Manual');
            $table->string('batch_id')->nullable(); // For grouping auto-generated schedules
            
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            // Unique constraint: one entry per masjid, date, and waktu_solat
            $table->unique(['masjid_id', 'tarikh', 'waktu_solat'], 'jadual_imam_bilal_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadual_imam_bilal');
    }
};
