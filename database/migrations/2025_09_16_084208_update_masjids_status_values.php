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
        // Update existing 'inactive' status to 'rejected' for rejected masjids
        // and keep 'inactive' for truly inactive ones

        // Update any existing inactive masjids that were actually rejected
        DB::table('masjids')
            ->where('status', 'inactive')
            ->whereNotNull('catatan_kelulusan')
            ->where('catatan_kelulusan', 'like', '%Ditolak%')
            ->update(['status' => 'rejected']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert rejected status back to inactive
        DB::table('masjids')
            ->where('status', 'rejected')
            ->update(['status' => 'inactive']);
    }
};
