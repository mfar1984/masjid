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
        Schema::table('kariah', function (Blueprint $table) {
            // Add attachment columns for IC/Passport files
            $table->string('ic_depan_path')->nullable()->after('email');
            $table->string('ic_belakang_path')->nullable()->after('ic_depan_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kariah', function (Blueprint $table) {
            // Drop attachment columns
            $table->dropColumn(['ic_depan_path', 'ic_belakang_path']);
        });
    }
};
