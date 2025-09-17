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
            $table->string('bangsa')->after('telefon');
            $table->index('bangsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kariah', function (Blueprint $table) {
            $table->dropIndex(['bangsa']);
            $table->dropColumn('bangsa');
        });
    }
};
