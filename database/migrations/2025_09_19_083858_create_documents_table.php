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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('file_extension', 10);
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->string('file_hash', 64)->nullable(); // SHA256 hash for duplicate detection

            // Folder relationship
            $table->unsignedBigInteger('folder_id')->nullable();

            // Document metadata
            $table->json('metadata')->nullable(); // Store additional file metadata
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_shared')->default(false);
            $table->integer('download_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();

            // Version control
            $table->integer('version')->default(1);
            $table->unsignedBigInteger('parent_document_id')->nullable(); // For versioning

            // Data isolation - WAJIB untuk multi-tenant
            $table->unsignedBigInteger('masjid_id');

            // Audit fields
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('folder_id')->references('id')->on('document_folders')->onDelete('set null');
            $table->foreign('parent_document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for performance
            $table->index(['masjid_id', 'folder_id']);
            $table->index(['masjid_id', 'name']);
            $table->index(['masjid_id', 'file_extension']);
            $table->index('file_hash');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
