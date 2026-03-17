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
            // Add custom jawatan field for "Ahli Jawatankuasa" dynamic input
            $table->string('jawatan_custom')->nullable()->after('jawatan');
            
            // Add archived flag and timestamp
            $table->boolean('is_archived')->default(false)->after('status');
            $table->timestamp('archived_at')->nullable()->after('is_archived');
            $table->unsignedBigInteger('archived_by')->nullable()->after('archived_at');
            $table->foreign('archived_by')->references('id')->on('users')->onDelete('set null');
            
            // Add index for archived records
            $table->index('is_archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ajk', function (Blueprint $table) {
            $table->dropForeign(['archived_by']);
            $table->dropIndex(['is_archived']);
            $table->dropColumn(['jawatan_custom', 'is_archived', 'archived_at', 'archived_by']);
        });
    }
};
