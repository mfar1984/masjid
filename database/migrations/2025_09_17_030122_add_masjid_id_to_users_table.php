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
            // Multi-Masjid Support: Users belong to specific masjid
            // NULL masjid_id = Super Admin (can access all masjids)
            // Non-NULL masjid_id = Admin Masjid/Staff (specific masjid only)
            $table->unsignedBigInteger('masjid_id')->nullable()->after('phone');

            // Custom Role System: Users have one role
            $table->unsignedBigInteger('role_id')->nullable()->after('masjid_id');

            // Add indexes for performance
            $table->index(['masjid_id']);
            $table->index(['role_id']);

            // Foreign key constraints
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('set null');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys and columns
            $table->dropForeign(['masjid_id']);
            $table->dropForeign(['role_id']);
            $table->dropIndex(['masjid_id']);
            $table->dropIndex(['role_id']);
            $table->dropColumn(['masjid_id', 'role_id']);
        });
    }
};
