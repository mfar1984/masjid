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
        Schema::create('api_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('base_url');
            $table->string('version', 50)->default('v1');
            $table->string('auth_type', 100)->default('Bearer Token');
            $table->text('access_token')->nullable();
            $table->integer('rate_limit')->default(0); // 0 = unlimited
            $table->integer('timeout')->default(30);
            $table->integer('max_retries')->default(3);
            $table->string('ssl_verification', 50)->default('enabled');
            $table->string('logging_level', 50)->default('Info');
            $table->string('token_default_expiry', 20)->default('6h');
            $table->text('allowed_origins')->nullable();
            $table->text('default_abilities')->nullable(); // JSON array
            $table->string('token_name', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_configurations');
    }
};
