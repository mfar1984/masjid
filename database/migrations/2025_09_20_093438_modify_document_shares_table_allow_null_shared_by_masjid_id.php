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
            // Drop foreign key constraint first
            $table->dropForeign(['shared_by_masjid_id']);

            // Modify column to allow null (for Super Admin shares)
            $table->unsignedBigInteger('shared_by_masjid_id')->nullable()->change();

            // Re-add foreign key constraint with nullable support
            $table->foreign('shared_by_masjid_id')->references('id')->on('masjids')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_shares', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['shared_by_masjid_id']);

            // Revert column to not nullable
            $table->unsignedBigInteger('shared_by_masjid_id')->nullable(false)->change();

            // Re-add foreign key constraint
            $table->foreign('shared_by_masjid_id')->references('id')->on('masjids')->onDelete('cascade');
        });
    }
};
