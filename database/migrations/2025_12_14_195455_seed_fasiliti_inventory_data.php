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
        // Update existing fasiliti based on jenis_fasiliti
        // Unique items (quantity = 1, not countable)
        DB::table('senarai_fasiliti')
            ->whereIn('jenis_fasiliti', ['Dewan', 'Bilik', 'Padang', 'Tempat Letak Kereta'])
            ->update([
                'kuantiti_total' => 1,
                'is_countable' => false,
            ]);

        // Countable items (quantity > 1, countable)
        // For Aset type, we'll set default but admin can update later
        DB::table('senarai_fasiliti')
            ->where('jenis_fasiliti', 'Aset')
            ->update([
                'kuantiti_total' => 1, // Default, admin should update
                'is_countable' => true,
            ]);

        // Lain-lain - default to unique
        DB::table('senarai_fasiliti')
            ->where('jenis_fasiliti', 'Lain-lain')
            ->update([
                'kuantiti_total' => 1,
                'is_countable' => false,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse, columns will be dropped by main migration
    }
};
