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
        Schema::create('document_shares', function (Blueprint $table) {
            $table->id();

            // What is being shared (polymorphic - can be document or folder)
            $table->morphs('shareable'); // shareable_type, shareable_id

            // Who is sharing (owner masjid)
            $table->unsignedBigInteger('shared_by_masjid_id');
            $table->unsignedBigInteger('shared_by_user_id');

            // Who is receiving the share (target masjid)
            $table->unsignedBigInteger('shared_with_masjid_id');
            $table->unsignedBigInteger('shared_with_user_id')->nullable(); // Specific user or all users in masjid

            // Permission levels
            $table->enum('permission_level', ['view', 'comment', 'edit', 'full_access'])->default('view');

            // Share settings
            $table->boolean('can_download')->default(true);
            $table->boolean('can_share_further')->default(false); // Can recipient share to others
            $table->boolean('notify_on_access')->default(false);

            // Share link settings
            $table->string('share_token', 64)->nullable()->unique(); // For public share links
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_public_link')->default(false);
            $table->string('password_hash')->nullable(); // For password-protected shares

            // Access tracking
            $table->integer('access_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('first_accessed_at')->nullable();

            // Status
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('shared_by_masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('shared_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shared_with_masjid_id')->references('id')->on('masjids')->onDelete('cascade');
            $table->foreign('shared_with_user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes for performance (with custom names to avoid length issues)
            $table->index(['shared_by_masjid_id', 'shareable_type', 'shareable_id'], 'idx_shares_by_masjid_shareable');
            $table->index(['shared_with_masjid_id', 'status'], 'idx_shares_with_masjid_status');
            $table->index(['share_token'], 'idx_shares_token');
            $table->index(['expires_at', 'status'], 'idx_shares_expires_status');

            // Unique constraint to prevent duplicate shares
            $table->unique(['shareable_type', 'shareable_id', 'shared_by_masjid_id', 'shared_with_masjid_id', 'shared_with_user_id'], 'unique_share');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_shares');
    }
};
