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
        Schema::create('tetapan', function (Blueprint $table) {
            $table->id();
            $table->string('kunci'); // Setting key
            $table->string('nama'); // Display name
            $table->text('nilai'); // Setting value
            $table->string('jenis')->default('text'); // Type: text, number, boolean, email, date, file
            $table->text('penerangan')->nullable(); // Description
            $table->boolean('boleh_edit')->default(true); // Can be edited
            $table->string('kategori')->default('umum'); // Category: umum, sistem, emel, notifikasi
            $table->integer('susunan')->default(0); // Display order

            // Multi-Masjid Support: Settings isolated by masjid_id
            // Each masjid has their own settings - masjid_id is required
            // No global settings - every setting belongs to a specific masjid
            $table->unsignedBigInteger('masjid_id');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['masjid_id']);
            $table->index(['kategori']);
            $table->index(['susunan']);

            // Unique constraint: kunci must be unique within each masjid scope
            // This ensures each masjid can have their own version of the same setting key
            $table->unique(['masjid_id', 'kunci'], 'tetapan_masjid_kunci_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tetapan');
    }
};
