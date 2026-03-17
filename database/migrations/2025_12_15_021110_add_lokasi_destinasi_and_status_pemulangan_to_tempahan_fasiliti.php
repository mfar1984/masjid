<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Task 1.1: Add lokasi destinasi and status pemulangan fields to tempahan_fasiliti
     */
    public function up(): void
    {
        Schema::table('tempahan_fasiliti', function (Blueprint $table) {
            // Lokasi Destinasi fields
            $table->boolean('is_lokasi_luaran')->default(false)->after('catatan_kelulusan');
            $table->string('lokasi_destinasi', 255)->nullable()->after('is_lokasi_luaran');
            $table->string('nama_tempat_luaran', 255)->nullable()->after('lokasi_destinasi');
            $table->string('alamat_luaran_1', 255)->nullable()->after('nama_tempat_luaran');
            $table->string('alamat_luaran_2', 255)->nullable()->after('alamat_luaran_1');
            $table->string('poskod_luaran', 10)->nullable()->after('alamat_luaran_2');
            $table->string('bandar_luaran', 100)->nullable()->after('poskod_luaran');
            $table->string('negeri_luaran', 100)->nullable()->after('bandar_luaran');

            // Status Pemulangan
            $table->enum('status_pemulangan', ['Belum Pulang', 'Sudah Pulang', 'Lewat', 'Sebahagian'])
                  ->default('Belum Pulang')
                  ->after('negeri_luaran');
            $table->datetime('tarikh_sebenar_pulangan')->nullable()->after('status_pemulangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tempahan_fasiliti', function (Blueprint $table) {
            $table->dropColumn([
                'is_lokasi_luaran',
                'lokasi_destinasi',
                'nama_tempat_luaran',
                'alamat_luaran_1',
                'alamat_luaran_2',
                'poskod_luaran',
                'bandar_luaran',
                'negeri_luaran',
                'status_pemulangan',
                'tarikh_sebenar_pulangan',
            ]);
        });
    }
};
