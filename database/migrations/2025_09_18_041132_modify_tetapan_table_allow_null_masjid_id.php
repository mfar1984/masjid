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
        Schema::table('tetapan', function (Blueprint $table) {
            // Drop existing foreign key constraint
            $table->dropForeign(['masjid_id']);
            
            // Drop unique constraint that includes masjid_id
            $table->dropUnique('tetapan_masjid_kunci_unique');
            
            // Modify masjid_id to allow NULL for Super Admin personal settings
            $table->unsignedBigInteger('masjid_id')->nullable()->change();
            
            // Re-add foreign key constraint (nullable)
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            
            // Re-add unique constraint with nullable masjid_id
            // NULL values are treated as distinct, so multiple NULL masjid_id with same kunci is allowed
            $table->unique(['masjid_id', 'kunci'], 'tetapan_masjid_kunci_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tetapan', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['masjid_id']);
            
            // Drop unique constraint
            $table->dropUnique('tetapan_masjid_kunci_unique');
            
            // Revert masjid_id to NOT NULL
            $table->unsignedBigInteger('masjid_id')->nullable(false)->change();
            
            // Re-add foreign key constraint (not nullable)
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            
            // Re-add unique constraint
            $table->unique(['masjid_id', 'kunci'], 'tetapan_masjid_kunci_unique');
        });
    }
};