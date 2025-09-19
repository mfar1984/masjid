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
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('roles', 'masjid_id')) {
                // Multi-Masjid Support: Roles isolated by masjid_id
                // NULL masjid_id = System/Global roles (Super Admin only)
                // Non-NULL masjid_id = Masjid-specific roles (Admin Masjid can create)
                $table->unsignedBigInteger('masjid_id')->nullable()->after('is_active');

                // Add index for performance
                $table->index(['masjid_id']);

                // Foreign key constraint
                $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            }
        });

        // Update unique constraint to include masjid_id scope
        Schema::table('roles', function (Blueprint $table) {
            // Get database connection type
            $connection = Schema::getConnection();
            $driver = $connection->getDriverName();

            // Handle different database drivers
            if ($driver === 'sqlite') {
                // SQLite doesn't support dropping unique constraints easily
                // We'll handle this differently for SQLite
                try {
                    $table->unique(['name', 'masjid_id'], 'roles_name_masjid_unique');
                } catch (\Exception $e) {
                    // Constraint might already exist, continue
                }
            } else {
                // For MySQL/PostgreSQL
                try {
                    // Drop existing unique constraint
                    $table->dropUnique(['name']);
                } catch (\Exception $e) {
                    // Constraint might not exist, continue
                }

                try {
                    // Add new unique constraint: name must be unique within same masjid scope
                    // System roles (masjid_id = NULL) have global unique names
                    // Masjid roles can have same name across different masjids
                    $table->unique(['name', 'masjid_id']);
                } catch (\Exception $e) {
                    // Constraint might already exist, continue
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Check if column exists before dropping
            if (Schema::hasColumn('roles', 'masjid_id')) {
                // Get database connection type
                $connection = Schema::getConnection();
                $driver = $connection->getDriverName();

                if ($driver === 'sqlite') {
                    // SQLite handling
                    try {
                        $table->dropUnique('roles_name_masjid_unique');
                    } catch (\Exception $e) {
                        // Constraint might not exist
                    }
                } else {
                    // MySQL/PostgreSQL handling
                    try {
                        // Drop new unique constraint
                        $table->dropUnique(['name', 'masjid_id']);
                    } catch (\Exception $e) {
                        // Constraint might not exist
                    }

                    try {
                        // Restore original unique constraint
                        $table->unique(['name']);
                    } catch (\Exception $e) {
                        // Constraint might already exist
                    }
                }

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
