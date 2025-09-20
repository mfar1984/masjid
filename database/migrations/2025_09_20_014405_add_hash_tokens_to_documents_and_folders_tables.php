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
        // Add hash tokens to document_folders table
        Schema::table('document_folders', function (Blueprint $table) {
            $table->string('hash_token', 32)->unique()->nullable()->after('id');
            $table->index('hash_token');
        });

        // Add hash tokens to documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->string('hash_token', 32)->unique()->nullable()->after('id');
            $table->index('hash_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_folders', function (Blueprint $table) {
            $table->dropIndex(['hash_token']);
            $table->dropColumn('hash_token');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['hash_token']);
            $table->dropColumn('hash_token');
        });
    }
};
