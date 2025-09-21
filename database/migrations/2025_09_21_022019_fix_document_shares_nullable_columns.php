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
        Schema::table('document_shares', function (Blueprint $table) {
            // Make shared_with_masjid_id and shared_with_user_id nullable for public links
            $table->unsignedBigInteger('shared_with_masjid_id')->nullable()->change();
            $table->unsignedBigInteger('shared_with_user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_shares', function (Blueprint $table) {
            // Revert to not nullable (but this might fail if there are NULL values)
            $table->unsignedBigInteger('shared_with_masjid_id')->nullable(false)->change();
            $table->unsignedBigInteger('shared_with_user_id')->nullable(false)->change();
        });
    }
};
