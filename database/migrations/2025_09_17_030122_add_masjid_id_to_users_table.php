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
        Schema::table('users', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('users', 'masjid_id')) {
                // Multi-Masjid Support: Users belong to specific masjid
                // NULL masjid_id = Super Admin (can access all masjids)
                // Non-NULL masjid_id = Admin Masjid/Staff (specific masjid only)
                $table->unsignedBigInteger('masjid_id')->nullable()->after('phone');

                // Add index for performance
                $table->index(['masjid_id']);

                // Foreign key constraint
                $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if column exists before dropping
            if (Schema::hasColumn('users', 'masjid_id')) {
                try {
                    // Drop foreign key and column
                    $table->dropForeign(['masjid_id']);
                    $table->dropIndex(['masjid_id']);
                    $table->dropColumn('masjid_id');
                } catch (\Exception $e) {
                    // Foreign key or index might not exist
                }
            }
        });
    }
};
