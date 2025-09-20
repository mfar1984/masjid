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
            // Add masjid_id for data isolation (WAJIB)
            $table->unsignedBigInteger('masjid_id')->nullable()->after('id');
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            
            // Add jantina field
            $table->enum('jantina', ['Lelaki', 'Perempuan', 'Tidak Dinyatakan'])->nullable()->after('bangsa');
            
            // Add attachment fields for IC/Passport
            $table->json('ic_passport_attachments')->nullable()->after('email');
            
            // Remove zon field and related index
            $table->dropIndex(['zon']); // Drop index first
            $table->dropColumn('zon');
            
            // Add indexes for new fields
            $table->index('masjid_id');
            $table->index('jantina');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kariah', function (Blueprint $table) {
            // Remove new fields
            $table->dropForeign(['masjid_id']);
            $table->dropIndex(['masjid_id']);
            $table->dropIndex(['jantina']);
            $table->dropColumn(['masjid_id', 'jantina', 'ic_passport_attachments']);
            
            // Restore zon field
            $table->string('zon')->nullable()->after('status');
            $table->index('zon');
        });
    }
};
