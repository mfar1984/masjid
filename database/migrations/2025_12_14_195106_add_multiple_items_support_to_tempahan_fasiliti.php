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
        // 1. Add columns to senarai_fasiliti for inventory management
        Schema::table('senarai_fasiliti', function (Blueprint $table) {
            $table->integer('kuantiti_total')->default(1)->after('kategori_fasiliti')
                ->comment('Total quantity available (1 for unique items like Dewan, >1 for countable items like Kerusi)');
            $table->boolean('is_countable')->default(false)->after('kuantiti_total')
                ->comment('TRUE if item can be counted (meja, kerusi), FALSE if unique (dewan, bilik)');
        });

        // 2. Create tempahan_fasiliti_items table for multiple items per booking
        Schema::create('tempahan_fasiliti_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tempahan_fasiliti_id')->constrained('tempahan_fasiliti')->onDelete('cascade');
            $table->foreignId('senarai_fasiliti_id')->constrained('senarai_fasiliti')->onDelete('restrict');
            $table->integer('quantity')->default(1)->comment('Quantity booked for this item');
            $table->decimal('harga_per_unit', 10, 2)->comment('Price per unit based on unit_tempoh');
            $table->decimal('subtotal', 10, 2)->comment('quantity * harga_per_unit * tempoh_sewa');
            $table->enum('status_item', ['Aktif', 'Dibatalkan'])->default('Aktif')
                ->comment('Status for individual item cancellation');
            $table->foreignId('dibatalkan_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tarikh_dibatalkan')->nullable();
            $table->text('sebab_batal_item')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('tempahan_fasiliti_id');
            $table->index('senarai_fasiliti_id');
            $table->index('status_item');
        });

        // 3. Remove single fasiliti reference from tempahan_fasiliti
        // But keep it temporarily for data migration
        Schema::table('tempahan_fasiliti', function (Blueprint $table) {
            $table->foreignId('senarai_fasiliti_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tempahan_fasiliti_items table
        Schema::dropIfExists('tempahan_fasiliti_items');

        // Remove columns from senarai_fasiliti
        Schema::table('senarai_fasiliti', function (Blueprint $table) {
            $table->dropColumn(['kuantiti_total', 'is_countable']);
        });

        // Restore senarai_fasiliti_id as required
        Schema::table('tempahan_fasiliti', function (Blueprint $table) {
            $table->foreignId('senarai_fasiliti_id')->nullable(false)->change();
        });
    }
};
