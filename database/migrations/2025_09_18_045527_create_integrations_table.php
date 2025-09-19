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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis'); // API, Email, Weather, etc.
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Dalam Pembangunan'])->default('Tidak Aktif');
            $table->text('konfigurasi')->nullable(); // JSON configuration
            $table->text('penerangan')->nullable();
            $table->string('url_endpoint')->nullable();
            $table->string('api_key')->nullable();
            $table->timestamp('terakhir_sync')->nullable();
            
            // Multi-Masjid Support: Integrations isolated by masjid_id
            $table->unsignedBigInteger('masjid_id');
            
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['masjid_id', 'status']);
            $table->index(['masjid_id', 'jenis']);
            
            // Unique constraint: nama must be unique within each masjid scope
            $table->unique(['masjid_id', 'nama'], 'integrations_masjid_nama_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};