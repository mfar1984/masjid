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
        Schema::table('ajk', function (Blueprint $table) {
            $table->string('gambar_path')->nullable()->after('surat_lantikan_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ajk', function (Blueprint $table) {
            $table->dropColumn('gambar_path');
        });
    }
};
