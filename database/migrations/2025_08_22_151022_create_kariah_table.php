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
        Schema::create('kariah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_ic', 14)->unique();
            $table->string('telefon', 15);
            $table->string('bangsa');
            $table->date('tarikh_keahlian');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->string('zon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index(['nama', 'no_ic']);
            $table->index('status');
            $table->index('zon');
            $table->index('bangsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kariah');
    }
};
