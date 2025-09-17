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
        Schema::table('roles', function (Blueprint $table) {
            // Multi-Masjid Support: Roles isolated by masjid_id
            // NULL masjid_id = System/Global roles (Super Admin only)
            // Non-NULL masjid_id = Masjid-specific roles (Admin Masjid can create)
            $table->unsignedBigInteger('masjid_id')->nullable()->after('is_active');

            // Add index for performance
            $table->index(['masjid_id']);

            // Foreign key constraint
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
        });

        // Update unique constraint to include masjid_id scope
        Schema::table('roles', function (Blueprint $table) {
            // Drop existing unique constraint
            $table->dropUnique(['name']);

            // Add new unique constraint: name must be unique within same masjid scope
            // System roles (masjid_id = NULL) have global unique names
            // Masjid roles can have same name across different masjids
            $table->unique(['name', 'masjid_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Drop new unique constraint
            $table->dropUnique(['name', 'masjid_id']);

            // Restore original unique constraint
            $table->unique(['name']);

            // Drop foreign key and column
            $table->dropForeign(['masjid_id']);
            $table->dropIndex(['masjid_id']);
            $table->dropColumn('masjid_id');
        });
    }
};
