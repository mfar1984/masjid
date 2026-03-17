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
        // Alter the enum column to add new values
        DB::statement("ALTER TABLE kategori_kewangan MODIFY COLUMN jenis_kategori ENUM('Pendapatan', 'Perbelanjaan', 'kategori_pendapatan', 'kaedah_bayaran', 'jenis_akaun', 'nama_bank') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE kategori_kewangan MODIFY COLUMN jenis_kategori ENUM('Pendapatan', 'Perbelanjaan') NOT NULL");
    }
};
