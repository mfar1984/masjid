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
            // Workflow fields
            $table->unsignedBigInteger('diluluskan_oleh')->nullable()->after('updated_by');
            $table->timestamp('tarikh_diluluskan')->nullable()->after('diluluskan_oleh');
            $table->text('catatan_kelulusan')->nullable()->after('tarikh_diluluskan');
            $table->timestamp('suspended_at')->nullable()->after('catatan_kelulusan');
            $table->unsignedBigInteger('suspended_by')->nullable()->after('suspended_at');

            // Update status enum to include workflow statuses
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Menunggu', 'Ditolak', 'Digantung'])->change();

            // Foreign key constraints
            $table->foreign('diluluskan_oleh')->references('id')->on('users')->onDelete('set null');
            $table->foreign('suspended_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kariah', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['diluluskan_oleh']);
            $table->dropForeign(['suspended_by']);

            // Drop columns
            $table->dropColumn([
                'diluluskan_oleh',
                'tarikh_diluluskan',
                'catatan_kelulusan',
                'suspended_at',
                'suspended_by'
            ]);

            // Revert status enum
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->change();
        });
    }
};
