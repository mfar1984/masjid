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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->boolean('is_system_role')->default(false);
            $table->boolean('is_active')->default(true);

            // Multi-Masjid Support: Roles isolated by masjid_id
            // NULL masjid_id = System/Global roles (Super Admin only)
            // Non-NULL masjid_id = Masjid-specific roles (Admin Masjid can create)
            $table->unsignedBigInteger('masjid_id')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['is_active']);
            $table->index(['is_system_role']);
            $table->index(['masjid_id']);

            // Unique constraint: name must be unique within same masjid scope
            // System roles (masjid_id = NULL) have global unique names
            // Masjid roles can have same name across different masjids
            $table->unique(['name', 'masjid_id']);

            // Foreign key constraint
            $table->foreign('masjid_id')->references('id')->on('masjids')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
