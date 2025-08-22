<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    private $apiKey;
    private $baseUrl = 'https://api.tomorrow.io/v4';

    public function __construct()
    {
        $this->apiKey = 'UINsXMQBMJemd36Z2AUaQ4e65nWw7i9V';
    }

    public function getCurrentWeather($location = 'Kuala Lumpur')
    {
        $cacheKey = "weather_{$location}";
        
        return Cache::remember($cacheKey, 600, function () use ($location) {
            try {
                $response = Http::get("{$this->baseUrl}/weather/realtime", [
                    'location' => $location,
                    'apikey' => $this->apiKey,
                    'units' => 'metric'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['data']['values'])) {
                        $values = $data['data']['values'];
                        
                        return [
                            'success' => true,
                            'data' => [
                                'temperature' => round($values['temperature']),
                                'weatherCode' => $values['weatherCode'],
                                'condition' => $this->getWeatherCondition($values['weatherCode']),
                                'city' => $location
                            ]
                        ];
                    }
                }
                
                return [
                    'success' => false,
                    'data' => $this->getDefaultWeather()
                ];
                
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'data' => $this->getDefaultWeather()
                ];
            }
        });
    }

    private function getWeatherCondition($weatherCode)
    {
        $conditions = [
            1000 => 'Clear',
            1100 => 'Mostly Clear',
            1101 => 'Partly Cloudy',
            1102 => 'Mostly Cloudy',
            2000 => 'Fog',
            4000 => 'Drizzle',
            4001 => 'Rain',
            4200 => 'Light Rain',
            4201 => 'Heavy Rain',
            5000 => 'Snow',
            5001 => 'Flurries',
            5100 => 'Light Snow',
            5101 => 'Heavy Snow',
            6000 => 'Freezing Drizzle',
            6200 => 'Light Freezing Rain',
            6201 => 'Heavy Freezing Rain',
            7000 => 'Ice Pellets',
            7101 => 'Heavy Ice Pellets',
            7102 => 'Light Ice Pellets',
            8000 => 'Thunderstorm'
        ];

        return $conditions[$weatherCode] ?? 'Clear';
    }

    private function getWeatherIcon($weatherCode)
    {
        $icons = [
            1000 => 'wb_sunny',
            1100 => 'wb_sunny',
            1101 => 'cloud',
            1102 => 'cloud',
            2000 => 'cloud',
            4000 => 'grain',
            4001 => 'opacity',
            4200 => 'grain',
            4201 => 'opacity',
            5000 => 'ac_unit',
            5001 => 'ac_unit',
            5100 => 'ac_unit',
            5101 => 'ac_unit',
            6000 => 'grain',
            6200 => 'grain',
            6201 => 'opacity',
            7000 => 'ac_unit',
            7101 => 'ac_unit',
            7102 => 'ac_unit',
            8000 => 'thunderstorm'
        ];

        return $icons[$weatherCode] ?? 'wb_sunny';
    }

    private function getDefaultWeather()
    {
        return [
            'temperature' => 24,
            'weatherCode' => 1000,
            'condition' => 'Clear',
            'city' => 'Kuala Lumpur'
        ];
    }
} 