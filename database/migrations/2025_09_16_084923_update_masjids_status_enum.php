<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check database driver and handle accordingly
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // MySQL doesn't support direct enum modification, so we need to use raw SQL
            DB::statement("ALTER TABLE masjids MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'suspended', 'rejected') DEFAULT 'pending'");
        } elseif ($driver === 'sqlite') {
            // SQLite doesn't have ENUM, so we just need to ensure the column exists
            // The values are handled by application logic
            if (!Schema::hasColumn('masjids', 'status')) {
                Schema::table('masjids', function (Blueprint $table) {
                    $table->string('status')->default('pending');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert any rejected status back to inactive before removing enum value
        DB::table('masjids')->where('status', 'rejected')->update(['status' => 'inactive']);

        // Check database driver and handle accordingly
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Revert enum to original values
            DB::statement("ALTER TABLE masjids MODIFY COLUMN status ENUM('active', 'inactive', 'pending', 'suspended') DEFAULT 'pending'");
        }
        // SQLite doesn't need specific handling for down migration
    }
};
