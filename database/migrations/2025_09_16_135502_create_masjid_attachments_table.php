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
        Schema::create('masjid_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('original_name'); // Original filename
            $table->string('file_path'); // Stored file path
            $table->string('file_type', 10); // pdf, png, jpeg, jpg
            $table->integer('file_size'); // File size in bytes
            $table->string('description')->nullable(); // Optional description
            $table->timestamps();

            // Index for faster queries
            $table->index('masjid_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masjid_attachments');
    }
};
