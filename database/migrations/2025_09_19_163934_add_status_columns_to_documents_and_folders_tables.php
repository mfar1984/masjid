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
        // Add status column to documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->enum('status', ['active', 'trash', 'spam'])->default('active')->after('updated_by');
            $table->timestamp('deleted_at')->nullable()->after('status');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');

            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'masjid_id']);
        });

        // Add status column to document_folders table
        Schema::table('document_folders', function (Blueprint $table) {
            $table->enum('status', ['active', 'trash', 'spam'])->default('active')->after('updated_by');
            $table->timestamp('deleted_at')->nullable()->after('status');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');

            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'masjid_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropIndex(['status', 'masjid_id']);
            $table->dropColumn(['status', 'deleted_at', 'deleted_by']);
        });

        Schema::table('document_folders', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropIndex(['status', 'masjid_id']);
            $table->dropColumn(['status', 'deleted_at', 'deleted_by']);
        });
    }
};
