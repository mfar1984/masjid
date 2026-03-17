<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Task 1.3: Add kuantiti_total field to senarai_fasiliti
     */
    public function up(): void
    {
        // Check if column already exists
        if (!Schema::hasColumn('senarai_fasiliti', 'kuantiti_total')) {
            Schema::table('senarai_fasiliti', function (Blueprint $table) {
                $table->integer('kuantiti_total')->default(1)->after('kapasiti_maksimum');
            });
        }

        // Update existing records to set kuantiti_total = 1 if null
        DB::table('senarai_fasiliti')->whereNull('kuantiti_total')->update(['kuantiti_total' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senarai_fasiliti', function (Blueprint $table) {
            $table->dropColumn('kuantiti_total');
        });
    }
};
