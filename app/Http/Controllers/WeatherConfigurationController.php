<?php

namespace App\Http\Controllers;

use App\Models\WeatherConfiguration;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WeatherConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $masjidId = null;

        if ($user->isSuperAdmin()) {
            $masjidId = $request->get('masjid_id', 'personal');
            if ($masjidId !== 'personal') {
                $masjidId = (int) $masjidId;
            } else {
                $masjidId = null; // Super admin personal settings
            }
        } else {
            $masjidId = $user->masjid_id;
        }

        $weatherConfig = WeatherConfiguration::where('masjid_id', $masjidId)->first();
        
        if (!$weatherConfig) {
            // Create default configuration if none exists
            $weatherConfig = WeatherConfiguration::create([
                'masjid_id' => $masjidId,
                'provider' => 'OpenWeatherMap',
                'api_key' => '',
                'base_url' => 'https://api.openweathermap.org/data/2.5',
                'default_location' => 'Bintulu',
                'latitude' => 3.1667000,
                'longitude' => 113.0333000,
                'units' => 'metric',
                'language' => 'ms',
                'update_frequency' => 30,
                'cache_duration' => 20,
                'is_active' => true
            ]);
        }
        
        return response()->json($weatherConfig);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $masjidId = null;

        if ($user->isSuperAdmin()) {
            $masjidId = $request->get('masjid_id', 'personal');
            if ($masjidId !== 'personal') {
                $masjidId = (int) $masjidId;
            } else {
                $masjidId = null;
            }
        } else {
            $masjidId = $user->masjid_id;
        }

        $validator = Validator::make($request->all(), [
            'provider' => 'required|string|in:OpenWeatherMap,Tomorrow.io',
            'api_key' => 'required|string|max:255',
            'base_url' => 'required|url|max:500',
            'default_location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'units' => 'required|in:metric,imperial',
            'language' => 'required|in:ms,en,zh,ta',
            'update_frequency' => 'required|integer|min:1|max:1440',
            'cache_duration' => 'required|integer|min:1|max:1440',
        ], [
            'provider.required' => 'Provider cuaca diperlukan',
            'provider.in' => 'Provider cuaca tidak sah',
            'api_key.required' => 'API Key diperlukan',
            'base_url.required' => 'Base URL diperlukan',
            'base_url.url' => 'Base URL mesti dalam format yang betul',
            'default_location.required' => 'Lokasi default diperlukan',
            'latitude.required' => 'Latitude diperlukan',
            'latitude.between' => 'Latitude mesti antara -90 hingga 90',
            'longitude.required' => 'Longitude diperlukan',
            'longitude.between' => 'Longitude mesti antara -180 hingga 180',
            'units.required' => 'Unit diperlukan',
            'units.in' => 'Unit mesti metric atau imperial',
            'language.required' => 'Bahasa diperlukan',
            'language.in' => 'Bahasa tidak sah',
            'update_frequency.required' => 'Kekerapan kemas kini diperlukan',
            'update_frequency.min' => 'Kekerapan kemas kini mesti sekurang-kurangnya 1 minit',
            'update_frequency.max' => 'Kekerapan kemas kini tidak boleh melebihi 1440 minit',
            'cache_duration.required' => 'Tempoh cache diperlukan',
            'cache_duration.min' => 'Tempoh cache mesti sekurang-kurangnya 1 minit',
            'cache_duration.max' => 'Tempoh cache tidak boleh melebihi 1440 minit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $weatherConfig = WeatherConfiguration::where('masjid_id', $masjidId)->first();
            
            if (!$weatherConfig) {
                $weatherConfig = new WeatherConfiguration();
                $weatherConfig->masjid_id = $masjidId;
            }
            
            $weatherConfig->fill($request->only([
                'provider', 'api_key', 'base_url', 'default_location',
                'latitude', 'longitude', 'units', 'language',
                'update_frequency', 'cache_duration'
            ]));
            $weatherConfig->last_update = now();
            $weatherConfig->save();

            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi cuaca berjaya dikemas kini',
                'data' => $weatherConfig
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat semasa menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test weather API connection
     */
    public function testApi(Request $request)
    {
        $user = Auth::user();
        $masjidId = null;

        if ($user->isSuperAdmin()) {
            $masjidId = $request->get('masjid_id', 'personal');
            if ($masjidId !== 'personal') {
                $masjidId = (int) $masjidId;
            } else {
                $masjidId = null;
            }
        } else {
            $masjidId = $user->masjid_id;
        }

        $weatherConfig = WeatherConfiguration::where('masjid_id', $masjidId)->first();
        
        if (!$weatherConfig || !$weatherConfig->api_key) {
            return response()->json([
                'success' => false,
                'message' => 'API Key tidak dikonfigurasi'
            ], 400);
        }

        try {
            $result = $this->testWeatherProvider($weatherConfig);
            
            if ($result['success']) {
                // Update current weather
                $weatherConfig->current_weather = $result['weather'];
                $weatherConfig->last_update = now();
                $weatherConfig->save();
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat sambungan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function testWeatherProvider($config)
    {
        switch ($config->provider) {
            case 'OpenWeatherMap':
                return $this->testOpenWeatherMap($config);
            case 'Tomorrow.io':
                return $this->testTomorrowIO($config);
            default:
                return [
                    'success' => false,
                    'message' => 'Provider tidak disokong'
                ];
        }
    }

    private function testOpenWeatherMap($config)
    {
        $url = $config->base_url . '/weather';
        $params = [
            'q' => $config->default_location,
            'appid' => $config->api_key,
            'units' => $config->units,
            'lang' => $config->language
        ];
        
        $response = Http::timeout(10)->get($url, $params);
        
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['cod']) && $data['cod'] == 200) {
                $weather = $data['weather'][0]['description'] . ', ' . round($data['main']['temp']) . '°C';
                return [
                    'success' => true,
                    'message' => 'API berfungsi dengan baik',
                    'weather' => $weather
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'API response tidak sah'
        ];
    }

    private function testTomorrowIO($config)
    {
        $url = $config->base_url . '/weather/realtime';
        $params = [
            'location' => $config->latitude . ',' . $config->longitude,
            'apikey' => $config->api_key,
            'units' => $config->units
        ];

        try {
            $response = Http::timeout(10)->get($url, $params);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['values'])) {
                    $temp = round($data['data']['values']['temperature']);
                    $weather = 'Temperature: ' . $temp . '°' . ($config->units === 'metric' ? 'C' : 'F');
                    return [
                        'success' => true,
                        'message' => 'Tomorrow.io berfungsi dengan baik',
                        'weather' => $weather
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Tomorrow.io tidak dapat diakses'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Refresh weather data
     */
    public function refreshData(Request $request)
    {
        $user = Auth::user();
        $masjidId = null;

        if ($user->isSuperAdmin()) {
            $masjidId = $request->get('masjid_id', 'personal');
            if ($masjidId !== 'personal') {
                $masjidId = (int) $masjidId;
            } else {
                $masjidId = null;
            }
        } else {
            $masjidId = $user->masjid_id;
        }

        $weatherConfig = WeatherConfiguration::where('masjid_id', $masjidId)->first();
        
        if (!$weatherConfig) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi cuaca tidak dijumpai'
            ], 404);
        }

        try {
            $result = $this->testWeatherProvider($weatherConfig);
            
            if ($result['success']) {
                $weatherConfig->current_weather = $result['weather'];
                $weatherConfig->last_update = now();
                $weatherConfig->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Data cuaca berjaya dikemas kini',
                    'weather' => $result['weather'],
                    'last_update' => $weatherConfig->formatted_last_update
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ralat semasa mengemas kini: ' . $e->getMessage()
            ], 500);
        }
    }
}
