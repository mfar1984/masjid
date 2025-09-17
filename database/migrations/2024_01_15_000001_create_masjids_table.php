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
        Schema::create('masjids', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('nama');
            $table->text('nama_penuh')->nullable();
            $table->string('kod_masjid', 20)->unique()->nullable();
            
            // Contact Information
            $table->text('alamat');
            $table->string('poskod', 10)->nullable();
            $table->string('bandar', 100)->nullable();
            $table->string('negeri', 50)->nullable();
            $table->string('telefon', 20)->nullable();
            $table->string('faks', 20)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('laman_web')->nullable();
            
            // Geographic Information
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Administrative Information
            $table->enum('kategori', ['masjid', 'surau', 'musolla'])->default('masjid');
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending');
            $table->date('tarikh_ditubuhkan')->nullable();
            $table->integer('bilangan_kariah')->default(0);
            $table->integer('kapasiti_jemaah')->nullable();
            
            // Registration Information
            $table->string('pendaftar_nama')->nullable();
            $table->string('pendaftar_telefon', 20)->nullable();
            $table->string('pendaftar_email')->nullable();
            $table->string('pendaftar_jawatan', 100)->nullable();
            
            // Approval Information
            $table->unsignedBigInteger('diluluskan_oleh')->nullable();
            $table->datetime('tarikh_diluluskan')->nullable();
            $table->text('catatan_kelulusan')->nullable();
            
            // Settings & Configuration
            $table->json('settings')->nullable();
            $table->string('logo_path', 500)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['kod_masjid']);
            $table->index(['status']);
            $table->index(['negeri']);
            $table->index(['kategori']);
            $table->index(['email']);
            
            // Foreign Keys will be added after users table exists
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masjids');
    }
};
