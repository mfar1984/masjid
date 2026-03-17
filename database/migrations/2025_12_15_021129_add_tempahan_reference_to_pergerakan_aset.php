<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Task 1.2: Add tempahan reference fields to pergerakan_aset
     */
    public function up(): void
    {
        Schema::table('pergerakan_aset', function (Blueprint $table) {
            // Reference to tempahan
            $table->foreignId('tempahan_fasiliti_id')
                  ->nullable()
                  ->after('senarai_aset_id')
                  ->constrained('tempahan_fasiliti')
                  ->nullOnDelete();
            
            $table->foreignId('tempahan_fasiliti_item_id')
                  ->nullable()
                  ->after('tempahan_fasiliti_id')
                  ->constrained('tempahan_fasiliti_items')
                  ->nullOnDelete();
            
            // Kuantiti for inventory tracking
            $table->integer('kuantiti')->default(1)->after('tempahan_fasiliti_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pergerakan_aset', function (Blueprint $table) {
            $table->dropForeign(['tempahan_fasiliti_id']);
            $table->dropForeign(['tempahan_fasiliti_item_id']);
            $table->dropColumn(['tempahan_fasiliti_id', 'tempahan_fasiliti_item_id', 'kuantiti']);
        });
    }
};
