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
        // Add jenis_derma_id to kutipan_dana table
        Schema::table('kutipan_dana', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_derma_id')->nullable()->after('kategori_kewangan_id');
            $table->foreign('jenis_derma_id')->references('id')->on('kategori_kewangan')->onDelete('set null');
        });

        // Add jenis_bil_id to perbelanjaan table
        Schema::table('perbelanjaan', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_bil_id')->nullable()->after('kategori_kewangan_id');
            $table->foreign('jenis_bil_id')->references('id')->on('kategori_kewangan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kutipan_dana', function (Blueprint $table) {
            $table->dropForeign(['jenis_derma_id']);
            $table->dropColumn('jenis_derma_id');
        });

        Schema::table('perbelanjaan', function (Blueprint $table) {
            $table->dropForeign(['jenis_bil_id']);
            $table->dropColumn('jenis_bil_id');
        });
    }
};
