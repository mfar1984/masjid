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
        Schema::create('document_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Hex color for folder
            $table->unsignedBigInteger('parent_folder_id')->nullable();
            $table->string('path')->nullable(); // Full path for quick access
            $table->integer('sort_order')->default(0);
            $table->boolean('is_shared')->default(false);
            $table->boolean('is_starred')->default(false);

            // Data isolation - WAJIB untuk multi-tenant
            $table->unsignedBigInteger('masjid_id');

            // Audit fields
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('parent_folder_id')->references('id')->on('document_folders')->onDelete('cascade');
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for performance
            $table->index(['masjid_id', 'parent_folder_id']);
            $table->index(['masjid_id', 'name']);
            $table->index('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_folders');
    }
};
