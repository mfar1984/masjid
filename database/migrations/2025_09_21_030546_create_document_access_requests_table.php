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
        Schema::create('document_access_requests', function (Blueprint $table) {
            $table->id();

            // Share token reference
            $table->string('share_token', 64);

            // Item being requested
            $table->string('item_type'); // 'document' or 'folder'
            $table->string('item_id'); // hash token

            // Requester information
            $table->unsignedBigInteger('requester_masjid_id');
            $table->unsignedBigInteger('requester_user_id');

            // Request details
            $table->text('reason');
            $table->enum('requested_permission', ['view', 'comment', 'edit'])->default('view');

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Approval details
            $table->unsignedBigInteger('reviewed_by_user_id')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('requester_masjid_id')->references('id')->on('masjids');
            $table->foreign('requester_user_id')->references('id')->on('users');
            $table->foreign('reviewed_by_user_id')->references('id')->on('users');

            // Indexes
            $table->index(['share_token', 'status']);
            $table->index(['requester_masjid_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_access_requests');
    }
};
