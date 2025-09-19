<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\WeatherConfiguration;

class WeatherController extends Controller
{
    public function getWeather()
    {
        try {
            // Get weather configuration based on user's masjid or super admin personal settings
            $user = Auth::user();
            $masjidId = null;

            if ($user && $user->isSuperAdmin()) {
                // Super Admin: Use personal settings (masjid_id = null)
                $masjidId = null;
            } elseif ($user) {
                // Regular user: Use their masjid settings
                $masjidId = $user->masjid_id;
            }

            // Get weather configuration
            $weatherConfig = WeatherConfiguration::where('masjid_id', $masjidId)->first();

            // If no configuration found, create default or use fallback
            if (!$weatherConfig || !$weatherConfig->api_key) {
                return $this->getFallbackWeather();
            }

            // Use configuration from database
            $lat = $weatherConfig->latitude;
            $lon = $weatherConfig->longitude;
            $apiKey = $weatherConfig->api_key;
            $baseUrl = $weatherConfig->base_url;
            $location = $weatherConfig->default_location;
            
            // Get weather data based on provider
            if ($weatherConfig->provider === 'OpenWeatherMap') {
                return $this->getOpenWeatherMapData($weatherConfig);
            } else {
                // Default to Tomorrow.io (AccuWeather or custom)
                return $this->getTomorrowIOData($weatherConfig);
            }

        } catch (\Exception $e) {
            return $this->getFallbackWeather();
        }
    }

    private function getTomorrowIOData($config)
    {
        try {
            // Get current weather
            $currentResponse = Http::get($config->base_url . '/weather/realtime', [
                'location' => $config->latitude . ',' . $config->longitude,
                'apikey' => $config->api_key,
                'units' => $config->units
            ]);

            // Get forecast for tomorrow
            $forecastResponse = Http::get($config->base_url . '/weather/forecast', [
                'location' => $config->latitude . ',' . $config->longitude,
                'apikey' => $config->api_key,
                'units' => $config->units,
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
                        'city' => $config->default_location,
                        'latitude' => $config->latitude,
                        'longitude' => $config->longitude,
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
            return $this->getFallbackWeather($config->default_location ?? 'Bintulu');
        }
    }

    private function getOpenWeatherMapData($config)
    {
        try {
            // Get current weather from OpenWeatherMap
            $currentResponse = Http::get($config->base_url . '/weather', [
                'lat' => $config->latitude,
                'lon' => $config->longitude,
                'appid' => $config->api_key,
                'units' => $config->units,
                'lang' => $config->language
            ]);

            // Get UV index from separate endpoint
            $uvResponse = Http::get($config->base_url . '/uvi', [
                'lat' => $config->latitude,
                'lon' => $config->longitude,
                'appid' => $config->api_key
            ]);

            $weatherData = [
                'success' => false,
                'data' => [
                    'current' => null,
                    'forecast' => null,
                    'location' => [
                        'city' => $config->default_location,
                        'latitude' => $config->latitude,
                        'longitude' => $config->longitude,
                        'country' => 'Malaysia',
                        'timezone' => 'Asia/Kuala_Lumpur'
                    ]
                ]
            ];

            // Get UV index from response
            $uvIndex = null;
            if ($uvResponse->successful()) {
                $uvData = $uvResponse->json();
                $uvIndex = isset($uvData['value']) ? round($uvData['value'], 1) : null;
            }

            if ($currentResponse->successful()) {
                $data = $currentResponse->json();
                if (isset($data['main'])) {
                    $weatherCode = $data['weather'][0]['id'] ?? 800;
                    $weatherData['data']['current'] = [
                        'temperature' => round($data['main']['temp']),
                        'weatherCode' => $weatherCode,
                        'condition' => $this->getWeatherCondition($weatherCode),
                        'humidity' => $data['main']['humidity'] ?? null,
                        'windSpeed' => isset($data['wind']['speed']) ? round($data['wind']['speed']) : null,
                        'pressure' => $data['main']['pressure'] ?? null,
                        'visibility' => isset($data['visibility']) ? round($data['visibility'] / 1000) : null,
                        'uvIndex' => $uvIndex,
                        'feelsLike' => isset($data['main']['feels_like']) ? round($data['main']['feels_like']) : null
                    ];
                }
            }

            // Set defaults if no data
            if ($weatherData['data']['current'] === null) {
                $weatherData['data']['current'] = $this->getDefaultCurrentWeather();
            }
            $weatherData['data']['forecast'] = $this->getDefaultForecast();
            $weatherData['success'] = true;

            return response()->json($weatherData, 200);

        } catch (\Exception $e) {
            return $this->getFallbackWeather($config->default_location ?? 'Bintulu');
        }
    }



    private function getFallbackWeather($location = 'Bintulu')
    {
        return response()->json([
            'success' => true,
            'message' => 'Using fallback weather data',
            'data' => [
                'current' => $this->getDefaultCurrentWeather(),
                'forecast' => $this->getDefaultForecast(),
                'location' => [
                    'city' => $location,
                    'latitude' => 3.1667,
                    'longitude' => 113.0333,
                    'country' => 'Malaysia',
                    'timezone' => 'Asia/Kuala_Lumpur'
                ]
            ]
        ]);
    }

    private function getDefaultCurrentWeather()
    {
        return [
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

    private function getDefaultForecast()
    {
        return [
            'date' => date('Y-m-d', strtotime('+1 day')),
            'temperature' => ['min' => 22, 'max' => 28],
            'weatherCode' => 1000,
            'condition' => 'Cerah',
            'precipitation' => 10,
            'humidity' => 75
        ];
    }

    private function getWeatherCondition($code)
    {
        // Tomorrow.io Weather Codes
        $tomorrowIOConditions = [
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

        // OpenWeatherMap Weather Codes
        $openWeatherConditions = [
            // Clear
            800 => 'Cerah',
            // Clouds
            801 => 'Sedikit Berawan',
            802 => 'Berawan Sebahagian',
            803 => 'Berawan',
            804 => 'Mendung',
            // Rain
            500 => 'Hujan Ringan',
            501 => 'Hujan Sederhana',
            502 => 'Hujan Lebat',
            503 => 'Hujan Sangat Lebat',
            504 => 'Hujan Ekstrem',
            511 => 'Hujan Sejuk',
            520 => 'Hujan Ringan',
            521 => 'Hujan Sederhana',
            522 => 'Hujan Lebat',
            531 => 'Hujan Tidak Menentu',
            // Drizzle
            300 => 'Gerimis Ringan',
            301 => 'Gerimis',
            302 => 'Gerimis Lebat',
            310 => 'Gerimis Ringan',
            311 => 'Gerimis',
            312 => 'Gerimis Lebat',
            313 => 'Hujan dan Gerimis',
            314 => 'Hujan Lebat dan Gerimis',
            321 => 'Gerimis',
            // Thunderstorm
            200 => 'Ribut Petir dengan Gerimis Ringan',
            201 => 'Ribut Petir dengan Gerimis',
            202 => 'Ribut Petir dengan Gerimis Lebat',
            210 => 'Ribut Petir Ringan',
            211 => 'Ribut Petir',
            212 => 'Ribut Petir Kuat',
            221 => 'Ribut Petir Tidak Menentu',
            230 => 'Ribut Petir dengan Gerimis Ringan',
            231 => 'Ribut Petir dengan Gerimis',
            232 => 'Ribut Petir dengan Gerimis Lebat',
            // Snow
            600 => 'Salji Ringan',
            601 => 'Salji',
            602 => 'Salji Lebat',
            611 => 'Hujan Ais',
            612 => 'Hujan Ais Ringan',
            613 => 'Hujan Ais',
            615 => 'Hujan Ringan dan Salji',
            616 => 'Hujan dan Salji',
            620 => 'Salji Ringan',
            621 => 'Salji',
            622 => 'Salji Lebat',
            // Atmosphere
            701 => 'Kabus',
            711 => 'Asap',
            721 => 'Jerebu',
            731 => 'Ribut Pasir/Debu',
            741 => 'Kabus',
            751 => 'Pasir',
            761 => 'Debu',
            762 => 'Abu Vulkanik',
            771 => 'Ribut Angin',
            781 => 'Puting Beliung'
        ];



        // Check Tomorrow.io codes first (4-digit codes)
        if (isset($tomorrowIOConditions[$code])) {
            return $tomorrowIOConditions[$code];
        }

        // Check OpenWeatherMap codes (3-digit codes)
        if (isset($openWeatherConditions[$code])) {
            return $openWeatherConditions[$code];
        }



        return 'Tidak Diketahui';
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