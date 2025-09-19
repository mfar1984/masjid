<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'masjid_id',
        'provider',
        'api_key',
        'base_url',
        'default_location',
        'latitude',
        'longitude',
        'units',
        'language',
        'update_frequency',
        'cache_duration',
        'last_update',
        'current_weather',
        'is_active'
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'update_frequency' => 'integer',
        'cache_duration' => 'integer',
        'last_update' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function getFormattedLastUpdateAttribute()
    {
        if ($this->last_update) {
            $diff = now()->diffInMinutes($this->last_update);
            if ($diff < 1) {
                return 'Baru sahaja';
            } elseif ($diff < 60) {
                return $diff . ' minit lalu';
            } elseif ($diff < 1440) {
                $hours = floor($diff / 60);
                return $hours . ' jam lalu';
            } else {
                $days = floor($diff / 1440);
                return $days . ' hari lalu';
            }
        }
        return 'Belum pernah';
    }

    public function getFormattedUnitsAttribute()
    {
        return $this->units === 'metric' ? 'Metric (Celsius)' : 'Imperial (Fahrenheit)';
    }

    public function getFormattedLanguageAttribute()
    {
        $languages = [
            'ms' => 'Bahasa Melayu',
            'en' => 'English',
            'zh' => '中文',
            'ta' => 'தமிழ்'
        ];
        return $languages[$this->language] ?? $this->language;
    }

    public function getProviderOptionsAttribute()
    {
        return [
            'OpenWeatherMap' => 'OpenWeatherMap',
            'Tomorrow.io' => 'Tomorrow.io'
        ];
    }

    public function getDefaultBaseUrlAttribute()
    {
        $urls = [
            'OpenWeatherMap' => 'https://api.openweathermap.org/data/2.5',
            'Tomorrow.io' => 'https://api.tomorrow.io/v4'
        ];
        return $urls[$this->provider] ?? '';
    }

    public static function getLocationCoordinates($location)
    {
        // Default coordinates for common Malaysian locations
        $locations = [
            'Kuala Lumpur' => ['lat' => 3.1390, 'lon' => 101.6869],
            'Bintulu' => ['lat' => 3.1667, 'lon' => 113.0333],
            'Sibu' => ['lat' => 2.2876, 'lon' => 111.8303],
            'Miri' => ['lat' => 4.4148, 'lon' => 113.9917],
            'Kuching' => ['lat' => 1.5533, 'lon' => 110.3592],
            'Johor Bahru' => ['lat' => 1.4927, 'lon' => 103.7414],
            'Penang' => ['lat' => 5.4164, 'lon' => 100.3327],
            'Ipoh' => ['lat' => 4.5975, 'lon' => 101.0901],
            'Shah Alam' => ['lat' => 3.0733, 'lon' => 101.5185],
            'Petaling Jaya' => ['lat' => 3.1073, 'lon' => 101.6067]
        ];

        foreach ($locations as $city => $coords) {
            if (stripos($location, $city) !== false) {
                return $coords;
            }
        }

        // Default to Bintulu if not found
        return ['lat' => 3.1667, 'lon' => 113.0333];
    }
}
