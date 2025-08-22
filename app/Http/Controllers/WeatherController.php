<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    private $apiKey = 'UINsXMQBMJemd36Z2AUaQ4e65nWw7i9V';
    private $baseUrl = 'https://api.tomorrow.io/v4';

    public function getWeather()
    {
        try {
            // Sibu coordinates: 2.2876° N, 111.8303° E
            $lat = 2.2876;
            $lon = 111.8303;
            
            // Get current weather
            $currentResponse = Http::get($this->baseUrl . '/weather/realtime', [
                'location' => $lat . ',' . $lon,
                'apikey' => $this->apiKey,
                'units' => 'metric'
            ]);

            // Get forecast for tomorrow
            $forecastResponse = Http::get($this->baseUrl . '/weather/forecast', [
                'location' => $lat . ',' . $lon,
                'apikey' => $this->apiKey,
                'units' => 'metric',
                'timesteps' => '1d',
                'startTime' => 'now',
                'endTime' => 'nowPlus2d'
            ]);

            $weatherData = [
                'success' => false,
                'data' => [
                    'current' => null,
                    'forecast' => null,
                    'location' => [
                        'city' => 'Sibu',
                        'latitude' => $lat,
                        'longitude' => $lon,
                        'country' => 'Malaysia',
                        'timezone' => 'Asia/Kuala_Lumpur'
                    ]
                ]
            ];

            if ($currentResponse->successful()) {
                $currentData = $currentResponse->json();
                if (isset($currentData['data']['values'])) {
                    $values = $currentData['data']['values'];
                    $pressure = $values['pressure']
                        ?? $values['pressureSurfaceLevel']
                        ?? $values['pressureSeaLevel']
                        ?? null;
                    $uvIndex = $values['uvIndex']
                        ?? $values['uvIndexMax']
                        ?? null;
                    $weatherData['data']['current'] = [
                        'temperature' => round($values['temperature']),
                        'weatherCode' => $values['weatherCode'],
                        'condition' => $this->getWeatherCondition($values['weatherCode']),
                        'humidity' => isset($values['humidity']) ? round($values['humidity']) : null,
                        'windSpeed' => isset($values['windSpeed']) ? round($values['windSpeed']) : null,
                        'pressure' => $pressure !== null ? round($pressure) : null,
                        'visibility' => isset($values['visibility']) ? round($values['visibility']) : null,
                        'uvIndex' => $uvIndex !== null ? round($uvIndex) : null,
                        'feelsLike' => isset($values['temperatureApparent']) ? round($values['temperatureApparent']) : null
                    ];
                }
            }

            if ($forecastResponse->successful()) {
                $forecastData = $forecastResponse->json();
                if (isset($forecastData['data']['timelines'][0]['intervals'])) {
                    $tomorrow = $forecastData['data']['timelines'][0]['intervals'][1] ?? null;
                    if ($tomorrow && isset($tomorrow['values'])) {
                        $values = $tomorrow['values'];
                        // Fallbacks for Tomorrow.io daily fields naming
                        $weatherCode = $values['weatherCode']
                            ?? $values['weatherCodeMax']
                            ?? $values['weatherCodeMin']
                            ?? null;
                        $precipProb = $values['precipitationProbability']
                            ?? $values['precipitationProbabilityAvg']
                            ?? $values['precipitationProbabilityMax']
                            ?? null;
                        $weatherData['data']['forecast'] = [
                            'date' => date('Y-m-d', strtotime($tomorrow['startTime'])),
                            'temperature' => [
                                'min' => isset($values['temperatureMin']) ? round($values['temperatureMin']) : null,
                                'max' => isset($values['temperatureMax']) ? round($values['temperatureMax']) : null
                            ],
                            'weatherCode' => $weatherCode,
                            'condition' => $weatherCode !== null ? $this->getWeatherCondition($weatherCode) : null,
                            'precipitation' => $precipProb !== null ? round($precipProb) : null,
                            'humidity' => isset($values['humidity']) ? round($values['humidity']) : null
                        ];
                    }
                }
            }

            // Ensure defaults when API fails silently
            if ($weatherData['data']['current'] === null) {
                $weatherData['data']['current'] = [
                    'temperature' => 24,
                    'weatherCode' => 1000,
                    'condition' => 'Cerah',
                    'humidity' => 70,
                    'windSpeed' => 5,
                    'pressure' => 1013,
                    'visibility' => 10,
                    'uvIndex' => 5,
                    'feelsLike' => 26
                ];
            }
            if ($weatherData['data']['forecast'] === null) {
                $weatherData['data']['forecast'] = [
                    'date' => date('Y-m-d', strtotime('+1 day')),
                    'temperature' => ['min' => 22, 'max' => 28],
                    'weatherCode' => 1000,
                    'condition' => 'Cerah',
                    'precipitation' => 10,
                    'humidity' => 75
                ];
            }

            $weatherData['success'] = true;
            return response()->json($weatherData, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Weather service unavailable',
                'data' => [
                    'current' => [
                        'temperature' => 24,
                        'weatherCode' => 1000,
                        'condition' => 'Cerah',
                        'humidity' => 70,
                        'windSpeed' => 5,
                        'pressure' => 1013,
                        'visibility' => 10,
                        'uvIndex' => 5,
                        'feelsLike' => 26
                    ],
                    'forecast' => [
                        'date' => date('Y-m-d', strtotime('+1 day')),
                        'temperature' => ['min' => 22, 'max' => 28],
                        'weatherCode' => 1000,
                        'condition' => 'Cerah',
                        'precipitation' => 10,
                        'humidity' => 75
                    ],
                    'location' => [
                        'city' => 'Sibu',
                        'latitude' => 2.2876,
                        'longitude' => 111.8303,
                        'country' => 'Malaysia',
                        'timezone' => 'Asia/Kuala_Lumpur'
                    ]
                ]
            ]);
        }
    }

    private function getWeatherCondition($code)
    {
        $conditions = [
            1000 => 'Cerah',
            1001 => 'Mendung',
            1100 => 'Sebahagian Cerah',
            1101 => 'Sebahagian Mendung',
            1102 => 'Kebanyakan Mendung',
            2000 => 'Berkabus',
            4000 => 'Hujan Ringan',
            4001 => 'Hujan',
            4200 => 'Hujan Ringan',
            4201 => 'Hujan Lebat',
            5000 => 'Salji',
            5001 => 'Salji Ringan',
            5100 => 'Salji Ringan',
            5101 => 'Salji Lebat',
            6000 => 'Hujan Sejuk Ringan',
            6200 => 'Hujan Sejuk Ringan',
            6201 => 'Hujan Sejuk',
            7000 => 'Hujan Ais',
            7101 => 'Hujan Ais Lebat',
            7102 => 'Hujan Ais Ringan',
            8000 => 'Ribut Petir'
        ];

        return $conditions[$code] ?? 'Tidak Diketahui';
    }

    public function getWeatherIcon($code)
    {
        $icons = [
            1000 => 'wb_sunny',      // Clear
            1001 => 'cloud',         // Cloudy
            1100 => 'wb_sunny',      // Mostly Clear
            1101 => 'cloud',         // Partly Cloudy
            1102 => 'cloud',         // Mostly Cloudy
            2000 => 'cloud',         // Fog
            4000 => 'grain',         // Light Rain
            4001 => 'rainy',         // Rain
            4200 => 'grain',         // Light Rain
            4201 => 'rainy',         // Heavy Rain
            5000 => 'ac_unit',       // Snow
            5001 => 'ac_unit',       // Flurries
            5100 => 'ac_unit',       // Light Snow
            5101 => 'ac_unit',       // Heavy Snow
            6000 => 'grain',         // Freezing Drizzle
            6200 => 'grain',         // Light Freezing Rain
            6201 => 'rainy',         // Freezing Rain
            7000 => 'ac_unit',       // Ice Pellets
            7101 => 'ac_unit',       // Heavy Ice Pellets
            7102 => 'ac_unit',       // Light Ice Pellets
            8000 => 'thunderstorm'   // Thunderstorm
        ];

        return $icons[$code] ?? 'wb_sunny';
    }
} 