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
        Schema::create('weather_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->nullable()->constrained('masjids')->onDelete('cascade');
            $table->enum('provider', ['OpenWeatherMap', 'WeatherAPI', 'AccuWeather'])->default('OpenWeatherMap');
            $table->string('api_key')->nullable();
            $table->string('base_url')->default('https://api.openweathermap.org/data/2.5');
            $table->string('default_location')->default('Bintulu');
            $table->decimal('latitude', 10, 7)->default(3.1667000);
            $table->decimal('longitude', 10, 7)->default(113.0333000);
            $table->enum('units', ['metric', 'imperial'])->default('metric');
            $table->enum('language', ['ms', 'en', 'zh', 'ta'])->default('ms');
            $table->integer('update_frequency')->default(30); // minutes
            $table->integer('cache_duration')->default(20); // minutes
            $table->timestamp('last_update')->nullable();
            $table->string('current_weather')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index for better performance
            $table->index(['masjid_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_configurations');
    }
};
