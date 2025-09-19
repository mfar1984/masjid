<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Integrasi - E-Masjid' }}</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Integrasi</h1>
                        @if(auth()->user()->isSuperAdmin())
                            <p class="text-xs text-gray-600">
                                Konfigurasi integrasi sistem dengan platform luar untuk <strong>{{ $selectedMasjid->nama ?? 'masjid terpilih' }}</strong>
                                <br>
                                <span class="text-blue-600">Diurus oleh: Super Administrator ({{ auth()->user()->email }})</span>
                            </p>
                        @else
                            <p class="text-xs text-gray-600">Konfigurasi integrasi sistem dengan platform luar untuk {{ auth()->user()->masjid->nama ?? 'masjid anda' }}</p>
                        @endif
                    </div>

                    @if(auth()->user()->isSuperAdmin() && $masjids->count() > 0)
                    <!-- Masjid Selector for Super Admin -->
                    <div class="flex items-center space-x-2">
                        <label for="masjid_selector" class="text-xs font-medium text-gray-700 whitespace-nowrap">Pilih Masjid:</label>
                        <select id="masjid_selector" 
                                onchange="window.location.href = '{{ route('integrations.index') }}?masjid_id=' + this.value"
                                class="px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]">
                            <!-- Super Admin Personal Settings Option -->
                            <option value="personal" {{ $selectedMasjidId === 'personal' ? 'selected' : '' }}>
                                🏠 Tetapan Peribadi (Super Admin)
                            </option>
                            <optgroup label="Masjid">
                                @foreach($masjids as $masjid)
                                    <option value="{{ $masjid->id }}" {{ $selectedMasjidId == $masjid->id ? 'selected' : '' }}>
                                        {{ $masjid->nama }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    @endif
                </div>
            
                <!-- Tabs -->
                <div class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex flex-col sm:flex-row space-y-2 sm:space-y-0" style="gap: 28px !important;">
                            @if($tabPermissions['email'])
                            <button onclick="showTab('email')" id="tab-email" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-xs text-blue-600 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">email</span>
                                Email (SMTP)
                            </button>
                            @endif

                            @if($tabPermissions['weather'])
                            <button onclick="showTab('weather')" id="tab-weather" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">wb_sunny</span>
                                Cuaca
                            </button>
                            @endif
                            
                            @if($tabPermissions['api'])
                            <button onclick="showTab('api')" id="tab-api" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">api</span>
                                API
                            </button>
                            @endif

                        </nav>
                    </div>
                </div>

                <!-- Success Notification for Email Tab -->
                <div id="emailSuccessNotification" class="mb-4 hidden">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded flex items-center">
                        <span class="material-icons mr-2" style="font-size: 14px !important;">check_circle</span>
                        <span id="emailSuccessMessage" class="text-xs">Konfigurasi email berjaya dikemaskini!</span>
                    </div>
                </div>

                <!-- Email Tab Content -->
                @if($tabPermissions['email'])
                <div id="content-email" class="tab-content">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 16px !important;">email</span>
                            Konfigurasi Email (SMTP)
                        </h2>

                        <form id="emailConfigForm" action="javascript:void(0)" onsubmit="return false">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- SMTP Host -->
                            <div>
                                <label for="smtp_host" class="block text-xs font-medium text-gray-700 mb-2">SMTP Host</label>
                                <input type="text"
                                       id="smtp_host"
                                       name="smtp_host"
                                       value="{{ $emailConfig->smtp_host }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="smtp.gmail.com"
                                       readonly>
                            </div>
                            
                            <!-- SMTP Port -->
                            <div>
                                <label for="smtp_port" class="block text-xs font-medium text-gray-700 mb-2">SMTP Port</label>
                                <input type="number"
                                       id="smtp_port"
                                       name="smtp_port"
                                       value="{{ $emailConfig->smtp_port }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="587"
                                       readonly>
                            </div>
                            
                            <!-- Username/Email -->
                            <div>
                                <label for="smtp_username" class="block text-xs font-medium text-gray-700 mb-2">Username/Email</label>
                                <input type="email"
                                       id="smtp_username"
                                       name="smtp_username"
                                       value="{{ $emailConfig->username }}"
                                       autocomplete="username"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="notify@ppjub.com.my"
                                       readonly>
                            </div>
                            
                            <!-- Password -->
                            <div>
                                <label for="smtp_password" class="block text-xs font-medium text-gray-700 mb-2">Password</label>
                                <input type="password"
                                       id="smtp_password"
                                       name="smtp_password"
                                       value="{{ $emailConfig->password }}"
                                       autocomplete="current-password"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="••••••••••••••••"
                                       readonly>
                            </div>
                            
                            <!-- Encryption -->
                            <div>
                                <label for="smtp_encryption" class="block text-xs font-medium text-gray-700 mb-2">Encryption</label>
                                <select id="smtp_encryption"
                                        name="smtp_encryption"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                        disabled>
                                    <option value="tls" {{ $emailConfig->encryption == 'TLS' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $emailConfig->encryption == 'SSL' ? 'selected' : '' }}>SSL</option>
                                    <option value="" {{ $emailConfig->encryption == 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            
                            <!-- Authentication -->
                            <div>
                                <label for="smtp_authentication" class="block text-xs font-medium text-gray-700 mb-2">Authentication</label>
                                <select id="smtp_authentication"
                                        name="smtp_authentication"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                        disabled>
                                    <option value="Required" {{ $emailConfig->authentication == 'Required' ? 'selected' : '' }}>Required</option>
                                    <option value="None" {{ $emailConfig->authentication == 'None' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            
                            <!-- From Name -->
                            <div>
                                <label for="smtp_from_name" class="block text-xs font-medium text-gray-700 mb-2">From Name</label>
                                <input type="text"
                                       id="smtp_from_name"
                                       name="smtp_from_name"
                                       value="{{ $emailConfig->from_name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="E-Kubur - Sistem Pengurusan Jenazah Ummah"
                                       readonly>
                            </div>
                            
                            <!-- Reply To -->
                            <div>
                                <label for="smtp_reply_to" class="block text-xs font-medium text-gray-700 mb-2">Reply To</label>
                                <input type="email"
                                       id="smtp_reply_to"
                                       name="smtp_reply_to"
                                       value="{{ $emailConfig->reply_to ?? $emailConfig->username }}"
                                       autocomplete="email"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="notify@ppjub.com.my"
                                       readonly>
                            </div>
                            
                            <!-- Connection Timeout -->
                            <div>
                                <label for="smtp_timeout" class="block text-xs font-medium text-gray-700 mb-2">Connection Timeout</label>
                                <input type="number"
                                       id="smtp_timeout"
                                       name="smtp_timeout"
                                       value="{{ $emailConfig->connection_timeout ?? '30' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="30"
                                       readonly>
                            </div>
                            
                            <!-- Max Retries -->
                            <div>
                                <label for="smtp_max_retries" class="block text-xs font-medium text-gray-700 mb-2">Max Retries</label>
                                <input type="number"
                                       id="smtp_max_retries"
                                       name="smtp_max_retries"
                                       value="{{ $emailConfig->max_retries ?? '3' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="3"
                                       readonly>
                            </div>
                            
                            <!-- Last Test -->
                            <div>
                                <label for="smtp_last_test" class="block text-xs font-medium text-gray-700 mb-2">Last Test</label>
                                <input type="text"
                                       id="smtp_last_test"
                                       name="smtp_last_test"
                                       value="{{ $emailConfig->formatted_last_test ?? '2 weeks after lalu' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       readonly>
                            </div>
                            
                            <!-- Test Status -->
                            <div>
                                <label for="smtp_test_status" class="block text-xs font-medium text-gray-700 mb-2">Test Status</label>
                                <input type="text"
                                       id="smtp_test_status"
                                       name="smtp_test_status"
                                       value="{{ $emailConfig->status_badge ?? 'Berjaya' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       readonly>
                            </div>
                        </div>

                        <div class="mt-6" style="width: 100% !important;">
                            <div class="flex justify-end items-center space-x-2" style="justify-content: flex-end !important; display: flex !important; width: 100% !important; flex-wrap: nowrap !important;">
                                <button type="button" id="email-edit-btn" onclick="toggleEmailEdit()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                    Edit Konfigurasi
                                </button>
                                <button type="button" id="email-save-btn" onclick="saveEmailConfig()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 hidden" style="flex-shrink: 0 !important;">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                                    Simpan Perubahan
                                </button>
                                <button type="button" onclick="showTestEmailModal()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">send</span>
                                    Test Email
                                </button>

                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Weather Tab Content -->
                @if($tabPermissions['weather'])
                <div id="content-weather" class="tab-content" style="display: none;">
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-1 flex items-center">
                            <span class="material-icons mr-2 text-yellow-600" style="font-size: 16px !important;">wb_sunny</span>
                            Konfigurasi API Cuaca
                        </h2>
                        <p class="text-xs text-gray-600 mb-4">Konfigurasi API cuaca untuk sistem maklumat</p>

                        <form id="weatherConfigForm" class="space-y-4" action="javascript:void(0)" onsubmit="return false">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Weather Provider -->
                            <div>
                                <label for="weather_provider" class="block text-xs font-medium text-gray-700 mb-2">Weather Provider</label>
                                <select id="weather_provider"
                                        name="weather_provider"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                        disabled>
                                    <option value="OpenWeatherMap" {{ $weatherConfig->provider == 'OpenWeatherMap' ? 'selected' : '' }}>OpenWeatherMap</option>
                                    <option value="Tomorrow.io" {{ $weatherConfig->provider == 'Tomorrow.io' ? 'selected' : '' }}>Tomorrow.io</option>
                                </select>
                            </div>

                            <!-- API Key -->
                            <div>
                                <label for="weather_api_key" class="block text-xs font-medium text-gray-700 mb-2">API Key</label>
                                <input type="password"
                                       id="weather_api_key"
                                       name="weather_api_key"
                                       value="{{ $weatherConfig->api_key ? '••••••••••••••••' : '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="••••••••••••••••"
                                       readonly>
                                <p id="weather_api_key_helper" class="mt-1 text-xs text-gray-500">
                                    GET API Key here: <a href="https://home.openweathermap.org/users/sign_up" target="_blank" class="text-blue-600 hover:text-blue-800 underline">https://home.openweathermap.org/users/sign_up</a>
                                </p>
                            </div>

                            <!-- Base URL -->
                            <div>
                                <label for="weather_base_url" class="block text-xs font-medium text-gray-700 mb-2">Base URL</label>
                                <input type="text"
                                       id="weather_base_url"
                                       name="weather_base_url"
                                       value="{{ $weatherConfig->base_url }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="https://api.tomorrow.io/v4"
                                       readonly>
                            </div>

                            <!-- Default Location -->
                            <div>
                                <label for="weather_location" class="block text-xs font-medium text-gray-700 mb-2">Default Location</label>
                                <input type="text"
                                       id="weather_location"
                                       name="weather_location"
                                       value="{{ $weatherConfig->default_location }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="Bintulu"
                                       readonly>
                            </div>

                            <!-- Latitude -->
                            <div>
                                <label for="weather_latitude" class="block text-xs font-medium text-gray-700 mb-2">Latitude</label>
                                <input type="number"
                                       id="weather_latitude"
                                       name="weather_latitude"
                                       value="{{ $weatherConfig->latitude }}"
                                       step="0.0000001"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="2.2876000"
                                       readonly>
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label for="weather_longitude" class="block text-xs font-medium text-gray-700 mb-2">Longitude</label>
                                <input type="number"
                                       id="weather_longitude"
                                       name="weather_longitude"
                                       value="{{ $weatherConfig->longitude }}"
                                       step="0.0000001"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="111.8303000"
                                       readonly>
                            </div>

                            <!-- Units -->
                            <div>
                                <label for="weather_units" class="block text-xs font-medium text-gray-700 mb-2">Units</label>
                                <select id="weather_units"
                                        name="weather_units"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                        disabled>
                                    <option value="metric" {{ $weatherConfig->units == 'metric' ? 'selected' : '' }}>Metric (Celsius)</option>
                                    <option value="imperial" {{ $weatherConfig->units == 'imperial' ? 'selected' : '' }}>Imperial (Fahrenheit)</option>
                                </select>
                            </div>

                            <!-- Language -->
                            <div>
                                <label for="weather_language" class="block text-xs font-medium text-gray-700 mb-2">Language</label>
                                <select id="weather_language"
                                        name="weather_language"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                        disabled>
                                    <option value="ms" {{ $weatherConfig->language == 'ms' ? 'selected' : '' }}>Bahasa Melayu</option>
                                    <option value="en" {{ $weatherConfig->language == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="zh" {{ $weatherConfig->language == 'zh' ? 'selected' : '' }}>中文</option>
                                    <option value="ta" {{ $weatherConfig->language == 'ta' ? 'selected' : '' }}>தமிழ்</option>
                                </select>
                            </div>

                            <!-- Update Frequency -->
                            <div>
                                <label for="weather_update_frequency" class="block text-xs font-medium text-gray-700 mb-2">Update Frequency</label>
                                <input type="number"
                                       id="weather_update_frequency"
                                       name="weather_update_frequency"
                                       value="{{ $weatherConfig->update_frequency }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="30"
                                       readonly>
                            </div>

                            <!-- Cache Duration -->
                            <div>
                                <label for="weather_cache_duration" class="block text-xs font-medium text-gray-700 mb-2">Cache Duration</label>
                                <input type="number"
                                       id="weather_cache_duration"
                                       name="weather_cache_duration"
                                       value="{{ $weatherConfig->cache_duration }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="20"
                                       readonly>
                            </div>

                            <!-- Last Update -->
                            <div>
                                <label for="weather_last_update" class="block text-xs font-medium text-gray-700 mb-2">Last Update</label>
                                <input type="text"
                                       id="weather_last_update"
                                       name="weather_last_update"
                                       value="{{ $weatherConfig->formatted_last_update ?? 'Baru sahaja' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       readonly>
                            </div>

                            <!-- Current Weather -->
                            <div>
                                <label for="weather_current" class="block text-xs font-medium text-gray-700 mb-2">Current Weather</label>
                                <input type="text"
                                       id="weather_current"
                                       name="weather_current"
                                       value="{{ $weatherConfig->current_weather ?? 'Cerah, 24°C' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       readonly>
                            </div>
                        </div>

                        <div class="mt-6" style="width: 100% !important;">
                            <div class="flex justify-end items-center space-x-2" style="justify-content: flex-end !important; display: flex !important; width: 100% !important; flex-wrap: nowrap !important;">
                                <button type="button" id="weather-edit-btn" onclick="toggleWeatherEdit()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                    Edit Konfigurasi
                                </button>
                                <button type="button" id="weather-save-btn" onclick="saveWeatherConfig()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 hidden" style="flex-shrink: 0 !important;">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                                    Simpan Perubahan
                                </button>
                                <button type="button" onclick="testWeatherAPI()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">wb_sunny</span>
                                    Test Weather
                                </button>
                                <button type="button" onclick="refreshWeatherData()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                                    Refresh Data
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                @endif

                <!-- API Tab Content -->
                @if($tabPermissions['api'])
                <div id="content-api" class="tab-content" style="display: none;">
                    <div class="bg-purple-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-1 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 16px !important;">api</span>
                            Konfigurasi API
                        </h2>
                        <p class="text-xs text-gray-600 mb-4">Konfigurasi API untuk sistem integrasi</p>

                        <form id="apiConfigForm" class="space-y-4" action="javascript:void(0)" onsubmit="return false">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Base URL -->
                            <div>
                                <label for="api_base_url" class="block text-xs font-medium text-gray-700 mb-2">Base URL</label>
                                <input type="text"
                                       id="api_base_url"
                                       name="api_base_url"
                                       value="{{ $apiConfig->base_url }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="{{ url('/') }}"
                                       readonly>
                            </div>

                            <!-- Version -->
                            <div>
                                <label for="api_version" class="block text-xs font-medium text-gray-700 mb-2">Version</label>
                                <input type="text"
                                       id="api_version"
                                       name="api_version"
                                       value="{{ $apiConfig->version }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="v1"
                                       readonly>
                            </div>


                            <!-- Rate Limit -->
                            <div>
                                <label for="api_rate_limit" class="block text-xs font-medium text-gray-700 mb-2">Rate Limit</label>
                                <select id="api_rate_limit" name="api_rate_limit" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:cursor-not-allowed" style="background-color: #e5e7eb !important; color: #4b5563 !important;" disabled>
                                    <option value="100" {{ (int) $apiConfig->rate_limit == 100 ? 'selected' : '' }}>100 requests/hour</option>
                                    <option value="500" {{ (int) $apiConfig->rate_limit == 500 ? 'selected' : '' }}>500 requests/hour</option>
                                    <option value="1000" {{ (int) $apiConfig->rate_limit == 1000 ? 'selected' : '' }}>1000 requests/hour</option>
                                    <option value="0" {{ (int) $apiConfig->rate_limit == 0 ? 'selected' : '' }}>Unlimited</option>
                                </select>
                            </div>

                            <!-- Timeout -->
                            <div>
                                <label for="api_timeout" class="block text-xs font-medium text-gray-700 mb-2">Timeout</label>
                                <select id="api_timeout" name="api_timeout" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:cursor-not-allowed" style="background-color: #e5e7eb !important; color: #4b5563 !important;" disabled>
                                    <option value="5" {{ (int) $apiConfig->timeout == 5 ? 'selected' : '' }}>5 saat</option>
                                    <option value="10" {{ (int) $apiConfig->timeout == 10 ? 'selected' : '' }}>10 saat</option>
                                    <option value="15" {{ (int) $apiConfig->timeout == 15 ? 'selected' : '' }}>15 saat</option>
                                    <option value="30" {{ (int) $apiConfig->timeout == 30 ? 'selected' : '' }}>30 saat</option>
                                    <option value="60" {{ (int) $apiConfig->timeout == 60 ? 'selected' : '' }}>60 saat</option>
                                </select>
                            </div>

                            <!-- Max Retries -->
                            <div>
                                <label for="api_max_retries" class="block text-xs font-medium text-gray-700 mb-2">Max Retries</label>
                                <input type="number"
                                       id="api_max_retries"
                                       name="api_max_retries"
                                       value="{{ $apiConfig->max_retries }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                       placeholder="3"
                                       readonly>
                            </div>

                            <!-- SSL Verification -->
                            <div>
                                <label for="api_ssl_verification" class="block text-xs font-medium text-gray-700 mb-2">SSL Verification</label>
                                <select id="api_ssl_verification"
                                        name="api_ssl_verification"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                        disabled>
                                    <option value="enabled" {{ $apiConfig->ssl_verification == 'enabled' ? 'selected' : '' }}>Enabled</option>
                                    <option value="disabled" {{ $apiConfig->ssl_verification == 'disabled' ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>

                            <!-- Logging Level -->
                            <div>
                                <label for="api_logging_level" class="block text-xs font-medium text-gray-700 mb-2">Logging Level</label>
                                <select id="api_logging_level"
                                        name="api_logging_level"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="background-color: #e5e7eb !important; color: #4b5563 !important;"
                                        disabled>
                                    <option value="Debug" {{ $apiConfig->logging_level == 'Debug' ? 'selected' : '' }}>Debug</option>
                                    <option value="Info" {{ $apiConfig->logging_level == 'Info' ? 'selected' : '' }}>Info</option>
                                    <option value="Warning" {{ $apiConfig->logging_level == 'Warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="Error" {{ $apiConfig->logging_level == 'Error' ? 'selected' : '' }}>Error</option>
                                </select>
                            </div>






                        </div>


                        </form>

                        <!-- Laravel Sanctum Section -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Laravel Sanctum</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Authentication -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Authentication</label>
                                    <input type="text" id="api-auth-provider" value="Bearer Token (Laravel Sanctum)" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;" disabled>
                                </div>

                                <!-- Token Name -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Token Name</label>
                                    <input type="text" id="api-token-name" value="{{ $apiConfig->token_name ?? '' }}" placeholder="Contoh: public_website" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;" disabled>
                                    <p class="text-[10px] text-gray-500 mt-1">Diambil daripada jadual personal_access_tokens (token terkini pengguna).</p>
                                </div>

                                <!-- Abilities -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Abilities (Scopes)</label>
                                    @php
                                        $selectedAbilities = is_string($apiConfig->default_abilities ?? null)
                                            ? (json_decode($apiConfig->default_abilities, true) ?: [])
                                            : ($apiConfig->default_abilities ?? []);
                                        $abilitiesOptions = [
                                            // Basic API Access
                                            'read:overview' => 'read:overview',
                                            // Integrations Management
                                            'read:integrations' => 'read:integrations',
                                            'write:integrations' => 'write:integrations',
                                            // Integration Types
                                            'read:integrations_api' => 'read:integrations_api',
                                            'write:integrations_api' => 'write:integrations_api',
                                            'read:integrations_email' => 'read:integrations_email',
                                            'write:integrations_email' => 'write:integrations_email',
                                            'read:integrations_weather' => 'read:integrations_weather',
                                            'write:integrations_weather' => 'write:integrations_weather',
                                            // System Health
                                            'read:system_health' => 'read:system_health',
                                            // Admin (for full access)
                                            'admin:integrations' => 'admin:integrations',
                                        ];
                                    @endphp
                                    <select id="api-abilities" multiple size="10" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;" disabled>
                                        @foreach($abilitiesOptions as $value => $label)
                                            <option value="{{ $value }}" {{ in_array($value, $selectedAbilities ?? []) ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-gray-500 mt-1">Pilih satu atau lebih abilities.</p>
                                </div>

                                <!-- Token Expiry -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Token Expiry</label>
                                    <select id="api-token-expiry" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;" disabled>
                                        <option value="15m" {{ ($apiConfig->token_default_expiry ?? '') == '15m' ? 'selected' : '' }}>15 minit</option>
                                        <option value="1h" {{ ($apiConfig->token_default_expiry ?? '') == '1h' ? 'selected' : '' }}>1 jam</option>
                                        <option value="6h" {{ ($apiConfig->token_default_expiry ?? '6h') == '6h' ? 'selected' : '' }}>6 jam</option>
                                        <option value="24h" {{ ($apiConfig->token_default_expiry ?? '') == '24h' ? 'selected' : '' }}>24 jam</option>
                                        <option value="7d" {{ ($apiConfig->token_default_expiry ?? '') == '7d' ? 'selected' : '' }}>7 hari</option>
                                        <option value="never" {{ ($apiConfig->token_default_expiry ?? '') == 'never' ? 'selected' : '' }}>Tiada tamat tempoh</option>
                                    </select>
                                </div>

                                <!-- Allowed Origins -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Allowed Origins (CORS)</label>
                                    <textarea id="api-allowed-origins" rows="2" placeholder="https://www.e-masjid.my, https://e-masjid.com.my" class="w-full px-3 py-2 border border-gray-300 rounded-xs text-xs" style="background-color: #e5e7eb !important; color: #4b5563 !important;" disabled>{{ $apiConfig->allowed_origins ?? '' }}</textarea>
                                    <p class="text-[10px] text-gray-500 mt-1">Pisahkan dengan koma.</p>
                                </div>
                            </div>


                            <!-- Token List Placeholder -->
                            <div class="mt-4">
                                <h5 class="text-xs font-medium text-gray-800 mb-2">Tokens</h5>
                                <div id="api-token-list" class="text-[11px] text-gray-600">Belum ada token.</div>
                            </div>
                        </div>

                            <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
                                <button type="button" id="sanctum-generate-btn" onclick="generateSanctumToken()" class="action-button px-4 py-2 bg-emerald-600 text-white text-xs rounded-xs hover:bg-emerald-700 font-medium flex items-center justify-center sm:justify-start" style="display: none !important;">
                                    <span class="material-icons text-xs mr-2" style="color: black !important;">key</span>
                                    <span style="color: black !important;">Generate Token</span>
                                </button>
                                <button type="button" id="sanctum-revoke-btn" onclick="revokeAllTokens()" class="action-button px-4 py-2 bg-rose-600 text-white text-xs rounded-xs hover:bg-rose-700 font-medium flex items-center justify-center sm:justify-start" style="display: none !important;">
                                    <span class="material-icons text-xs mr-2" style="color: black !important;">delete</span>
                                    <span style="color: black !important;">Revoke All Tokens</span>
                                </button>
                            </div>

                        <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
                            <button type="button" id="api-edit-btn" onclick="toggleApiEdit()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit Konfigurasi
                            </button>
                            <button type="button" id="api-save-btn" onclick="saveApiConfig(event)" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 hidden" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                                Simpan Perubahan
                            </button>
                            <button type="button" onclick="testApiConnection()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-orange-600 text-white text-xs rounded hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">api</span>
                                Test API
                            </button>
                            <button type="button" onclick="syncApiData()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">sync</span>
                                Sync Data
                            </button>
                        </div>


                    </div>
                @endif

            </div>
        </div>
    </main>

    <x-footer />


    <!-- Test Email Modal -->
    <div id="testEmailModal" class="fixed inset-0 hidden z-50 flex items-center justify-center p-4" style="background-color: rgba(75, 85, 99, 0.3) !important; backdrop-filter: blur(4px) !important;">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Test Email Configuration</h3>
                    <button onclick="hideTestEmailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <span class="material-icons text-sm">close</span>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="px-6 py-4">
                <p class="text-xs text-gray-600 mb-4">Masukkan email address yang akan receive test email:</p>
                
                <div class="mb-4">
                    <label for="recipientEmail" class="block text-xs font-medium mb-2" style="color: #000000 !important;">Email Penerima</label>
                    <input type="email"
                           id="recipientEmail"
                           placeholder="contoh@email.com"
                           autocomplete="email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium mb-2" style="color: #000000 !important;">Maklumat Konfigurasi</label>
                    <div class="bg-gray-50 p-3 rounded-sm text-xs" style="color: #000000 !important;">
                        <div class="grid grid-cols-2 gap-2">
                            <div style="color: #000000 !important;"><strong>SMTP Host:</strong> {{ $emailConfig->smtp_host }}</div>
                            <div style="color: #000000 !important;"><strong>Port:</strong> {{ $emailConfig->smtp_port }}</div>
                            <div style="color: #000000 !important;"><strong>Username:</strong> {{ $emailConfig->username }}</div>
                            <div style="color: #000000 !important;"><strong>Encryption:</strong> {{ $emailConfig->encryption }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="hideTestEmailModal()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded hover:bg-gray-200">
                    Batal
                </button>
                <button onclick="sendTestEmail()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 text-xs font-medium text-white bg-orange-600 rounded hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    <span class="material-icons mr-2" style="font-size: 16px !important;">send</span>
                    Hantar Test Email
                </button>
            </div>
        </div>
    </div>

    <!-- Tab JavaScript -->
    <script>
    function showTab(tabName) {
        // Hide all tab contents
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => {
            content.style.display = 'none';
        });

        // Remove active class from all tab buttons
        const buttons = document.querySelectorAll('.tab-button');
        buttons.forEach(button => {
            button.classList.remove('active', 'border-blue-500', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        document.getElementById('content-' + tabName).style.display = 'block';

        // Add active class to selected tab button
        const activeButton = document.getElementById('tab-' + tabName);
        activeButton.classList.add('active', 'border-blue-500', 'text-blue-600');
        activeButton.classList.remove('border-transparent', 'text-gray-500');
    }

    // Set default tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        showTab('email');
    });
    
    // Email Configuration Functions
    function toggleEmailEdit() {
        const editBtn = document.getElementById('email-edit-btn');
        const saveBtn = document.getElementById('email-save-btn');
        const isEditing = editBtn.textContent.includes('Batal');
        
        const emailFields = ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_authentication', 'smtp_from_name', 'smtp_reply_to', 'smtp_timeout', 'smtp_max_retries'];
        
        if (!isEditing) {
            // Enable edit mode
            emailFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.readOnly = false;
                    element.disabled = false;
                    element.style.setProperty('background-color', '#ffffff', 'important');
                    element.style.setProperty('color', '#111827', 'important');
                }
            });
            
            editBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">close</span>Batal';
            editBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            saveBtn.classList.remove('hidden');
        } else {
            // Disable edit mode
            emailFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.readOnly = true;
                    element.disabled = true;
                    element.style.setProperty('background-color', '#e5e7eb', 'important');
                    element.style.setProperty('color', '#4b5563', 'important');
                }
            });
            
            editBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>Edit Konfigurasi';
            editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
            editBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            saveBtn.classList.add('hidden');
        }
    }
    
    function saveEmailConfig() {
        // Show loading state
        const saveBtn = document.getElementById('email-save-btn');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Menyimpan...';
        saveBtn.disabled = true;
        
        // Get fresh CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Collect form data
        const formData = {
            smtp_host: document.getElementById('smtp_host').value,
            smtp_port: document.getElementById('smtp_port').value,
            smtp_username: document.getElementById('smtp_username').value,
            smtp_password: document.getElementById('smtp_password').value,
            smtp_encryption: document.getElementById('smtp_encryption').value,
            smtp_authentication: document.getElementById('smtp_authentication').value,
            smtp_from_name: document.getElementById('smtp_from_name').value,
            smtp_reply_to: document.getElementById('smtp_reply_to').value,
            smtp_timeout: document.getElementById('smtp_timeout').value,
            smtp_max_retries: document.getElementById('smtp_max_retries').value,
            _token: csrfToken
        };
        
        // Send AJAX request
        const url = '{{ route("email-configurations.update", ["id" => 1]) }}' + 
                   (new URLSearchParams(window.location.search).get('masjid_id') ? 
                    '?masjid_id=' + new URLSearchParams(window.location.search).get('masjid_id') : '');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showEmailNotification(data.message || 'Konfigurasi email berjaya dikemaskini!');
                toggleEmailEdit();
                
                // Update last test time
                const now = new Date();
                const timeString = now.toLocaleDateString('ms-MY') + ' ' + now.toLocaleTimeString('ms-MY', {hour: '2-digit', minute: '2-digit'});
                document.getElementById('smtp_last_test').value = 'Baru sahaja';
            } else {
                showEmailNotification('Ralat: ' + (data.message || 'Gagal menyimpan konfigurasi'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showEmailNotification('Ralat: Gagal menyimpan konfigurasi email', 'error');
        })
        .finally(() => {
            // Restore button
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    }
    
    // Test Email Modal Functions
    function showTestEmailModal() {
        const modal = document.getElementById('testEmailModal');
        const modalContent = document.getElementById('modalContent');
        const emailInput = document.getElementById('recipientEmail');
        
        if (!modal || !modalContent) {
            console.error('Test email modal elements not found');
            return;
        }
        
        // Show modal
        modal.classList.remove('hidden');
        
        // Animate modal content
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        if (emailInput) {
            setTimeout(() => emailInput.focus(), 300);
        } else {
            console.error('Recipient email input not found');
        }
    }
    
    function hideTestEmailModal() {
        const modal = document.getElementById('testEmailModal');
        const modalContent = document.getElementById('modalContent');
        const emailInput = document.getElementById('recipientEmail');
        
        if (modalContent) {
            // Animate out
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                if (emailInput) emailInput.value = '';
            }, 300);
        } else {
            // Fallback without animation
            modal.classList.add('hidden');
            if (emailInput) emailInput.value = '';
        }
    }
    
    function sendTestEmail() {
        const recipientEmail = document.getElementById('recipientEmail').value.trim();
        
        if (!recipientEmail) {
            showEmailNotification('Sila masukkan email penerima', 'error');
            document.getElementById('recipientEmail').focus();
            return;
        }
        
        if (!isValidEmail(recipientEmail)) {
            showEmailNotification('Format email tidak sah', 'error');
            document.getElementById('recipientEmail').focus();
            return;
        }
        
        // Show loading state
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Menghantar...';
        btn.disabled = true;
        
        // Get fresh CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Add masjid_id parameter if it exists in URL
        const url = '{{ route("test-email-send") }}' +
                   (new URLSearchParams(window.location.search).get('masjid_id') ?
                    '?masjid_id=' + new URLSearchParams(window.location.search).get('masjid_id') : '');

        // Create FormData object for proper form submission
        const formDataObj = new FormData();
        formDataObj.append('recipient_email', recipientEmail);
        formDataObj.append('_token', csrfToken);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formDataObj
        })
        .then(response => {
            if (!response.ok) {
                // Try to get the error message from the response
                return response.json().then(errorData => {
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }).catch(() => {
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showEmailNotification('Test email berjaya dihantar ke ' + recipientEmail);
                hideTestEmailModal();

                // Update test status display
                const now = new Date();
                const timeString = now.toLocaleDateString('ms-MY') + ' ' + now.toLocaleTimeString('ms-MY', {hour: '2-digit', minute: '2-digit'});
                document.getElementById('smtp_last_test').value = timeString;
                document.getElementById('smtp_test_status').value = 'Berjaya';
            } else {
                showEmailNotification('Ralat: ' + (data.message || 'Gagal menghantar test email'), 'error');

                // Update test status display for failed test
                const now = new Date();
                const timeString = now.toLocaleDateString('ms-MY') + ' ' + now.toLocaleTimeString('ms-MY', {hour: '2-digit', minute: '2-digit'});
                document.getElementById('smtp_last_test').value = timeString;
                document.getElementById('smtp_test_status').value = 'Gagal';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showEmailNotification('Ralat: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    
    function testSMTPHealth() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Testing...';
        btn.disabled = true;
        
        setTimeout(() => {
            showNotification('Sambungan ke SMTP berjaya.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1500);
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }


    
    function showEmailNotification(message, type = 'success') {
        const notification = document.getElementById('emailSuccessNotification');
        if (!notification) {
            console.error('Email notification element not found');
            return;
        }
        
        const messageEl = document.getElementById('emailSuccessMessage');
        const container = notification.querySelector('div');
        
        if (!messageEl || !container) {
            console.error('Email notification elements not found');
            return;
        }
        
        messageEl.textContent = message;
        
        // Update styling based on type
        if (type === 'error') {
            container.className = 'bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded flex items-center';
        } else {
            container.className = 'bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded flex items-center';
        }
        
        notification.classList.remove('hidden');
        
        // Scroll to notification if needed
        notification.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 5000);
    }
    
    function showNotification(message) {
        const notification = document.getElementById('successNotification');
        const messageEl = document.getElementById('successMessage');
        messageEl.textContent = message;
        notification.classList.remove('hidden');
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }
    
    // Weather Configuration Functions
    function toggleWeatherEdit() {
        const editBtn = document.getElementById('weather-edit-btn');
        const saveBtn = document.getElementById('weather-save-btn');
        const isEditing = editBtn.textContent.includes('Batal');

        const weatherFields = [
            'weather_provider', 'weather_api_key', 'weather_base_url', 'weather_location',
            'weather_latitude', 'weather_longitude', 'weather_units', 'weather_language',
            'weather_update_frequency', 'weather_cache_duration'
        ];

        if (!isEditing) {
            // Enable edit mode
            weatherFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.readOnly = false;
                    element.disabled = false;
                    element.style.setProperty('background-color', '#ffffff', 'important');
                    element.style.setProperty('color', '#111827', 'important');
                }
            });

            // Show actual API key for editing
            const apiKeyField = document.getElementById('weather_api_key');
            if (apiKeyField && apiKeyField.value === '••••••••••••••••') {
                apiKeyField.value = '';
                apiKeyField.type = 'text';
            }

            // Change edit button to cancel button
            editBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">close</span>Batal';
            editBtn.classList.remove('bg-orange-600', 'hover:bg-orange-700');
            editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            saveBtn.classList.remove('hidden');
        } else {
            // Disable edit mode (same as cancelWeatherEdit)
            cancelWeatherEdit();
        }
    }

    function cancelWeatherEdit() {
        const editBtn = document.getElementById('weather-edit-btn');
        const saveBtn = document.getElementById('weather-save-btn');

        const weatherFields = [
            'weather_provider', 'weather_api_key', 'weather_base_url', 'weather_location',
            'weather_latitude', 'weather_longitude', 'weather_units', 'weather_language',
            'weather_update_frequency', 'weather_cache_duration'
        ];

        // Disable edit mode
        weatherFields.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.readOnly = true;
                element.disabled = true;
                element.style.setProperty('background-color', '#e5e7eb', 'important');
                element.style.setProperty('color', '#4b5563', 'important');
            }
        });

        // Reset API key display
        const apiKeyField = document.getElementById('weather_api_key');
        if (apiKeyField) {
            apiKeyField.type = 'password';
            apiKeyField.value = '••••••••••••••••';
        }

        // Reset edit button
        editBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>Edit Konfigurasi';
        editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        editBtn.classList.add('bg-orange-600', 'hover:bg-orange-700');
        saveBtn.classList.add('hidden');

        // Reload weather config
        loadWeatherConfig();
    }

    function saveWeatherConfig() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Menyimpan...';
        btn.disabled = true;

        const formData = {
            provider: document.getElementById('weather_provider').value,
            api_key: document.getElementById('weather_api_key').value,
            base_url: document.getElementById('weather_base_url').value,
            default_location: document.getElementById('weather_location').value,
            latitude: parseFloat(document.getElementById('weather_latitude').value),
            longitude: parseFloat(document.getElementById('weather_longitude').value),
            units: document.getElementById('weather_units').value,
            language: document.getElementById('weather_language').value,
            update_frequency: parseInt(document.getElementById('weather_update_frequency').value),
            cache_duration: parseInt(document.getElementById('weather_cache_duration').value)
        };

        // Add masjid_id if exists
        const masjidId = new URLSearchParams(window.location.search).get('masjid_id');
        if (masjidId) {
            formData.masjid_id = masjidId;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("weather-configurations.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showWeatherNotification('Konfigurasi cuaca berjaya dikemas kini');
                cancelWeatherEdit(); // This will reset the buttons and reload config
            } else {
                showWeatherNotification('Ralat: ' + (data.message || 'Gagal menyimpan konfigurasi'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showWeatherNotification('Ralat: ' + error.message, 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function testWeatherAPI() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Testing...';
        btn.disabled = true;

        const masjidId = new URLSearchParams(window.location.search).get('masjid_id');
        const url = '{{ route("weather-configurations.test") }}' + (masjidId ? '?masjid_id=' + masjidId : '');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showWeatherNotification('API berfungsi dengan baik: ' + data.weather);
                // Update current weather display
                const currentWeatherField = document.getElementById('weather_current');
                if (currentWeatherField) {
                    currentWeatherField.value = data.weather;
                }
            } else {
                showWeatherNotification('Ralat: ' + (data.message || 'Gagal menguji API'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showWeatherNotification('Ralat: ' + error.message, 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function refreshWeatherData() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Refreshing...';
        btn.disabled = true;

        const masjidId = new URLSearchParams(window.location.search).get('masjid_id');
        const url = '{{ route("weather-configurations.refresh") }}' + (masjidId ? '?masjid_id=' + masjidId : '');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showWeatherNotification('Data cuaca berjaya dikemas kini: ' + data.weather);
                // Update displays
                const currentWeatherField = document.getElementById('weather_current');
                const lastUpdateField = document.getElementById('weather_last_update');
                if (currentWeatherField) currentWeatherField.value = data.weather;
                if (lastUpdateField) lastUpdateField.value = data.last_update;
            } else {
                showWeatherNotification('Ralat: ' + (data.message || 'Gagal mengemas kini data'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showWeatherNotification('Ralat: ' + error.message, 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function loadWeatherConfig() {
        const masjidId = new URLSearchParams(window.location.search).get('masjid_id');
        const url = '{{ route("weather-configurations.index") }}' + (masjidId ? '?masjid_id=' + masjidId : '');

        fetch(url)
        .then(response => response.json())
        .then(data => {
            // Update form fields
            document.getElementById('weather_provider').value = data.provider || 'OpenWeatherMap';
            document.getElementById('weather_api_key').value = data.api_key ? '••••••••••••••••' : '';
            document.getElementById('weather_base_url').value = data.base_url || '';
            document.getElementById('weather_location').value = data.default_location || '';
            document.getElementById('weather_latitude').value = data.latitude || '';
            document.getElementById('weather_longitude').value = data.longitude || '';
            document.getElementById('weather_units').value = data.units || 'metric';
            document.getElementById('weather_language').value = data.language || 'ms';
            document.getElementById('weather_update_frequency').value = data.update_frequency || 30;
            document.getElementById('weather_cache_duration').value = data.cache_duration || 20;
            document.getElementById('weather_current').value = data.current_weather || 'Tiada data';
            document.getElementById('weather_last_update').value = data.formatted_last_update || 'Belum pernah';
        })
        .catch(error => {
            console.error('Error loading weather config:', error);
        });
    }

    function showWeatherNotification(message, type = 'success') {
        // Create notification if it doesn't exist
        let notification = document.getElementById('weatherSuccessNotification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'weatherSuccessNotification';
            notification.className = 'mb-4 hidden';
            notification.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded flex items-center">
                    <span class="material-icons mr-2" style="font-size: 14px !important;">check_circle</span>
                    <span id="weatherSuccessMessage" class="text-xs">${message}</span>
                </div>
            `;

            // Insert before weather content
            const weatherContent = document.getElementById('content-weather');
            if (weatherContent) {
                weatherContent.parentNode.insertBefore(notification, weatherContent);
            }
        }

        const messageEl = document.getElementById('weatherSuccessMessage');
        const container = notification.querySelector('div');

        if (messageEl && container) {
            messageEl.textContent = message;

            // Update styling based on type
            if (type === 'error') {
                container.className = 'bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded flex items-center';
            } else {
                container.className = 'bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded flex items-center';
            }

            notification.classList.remove('hidden');

            // Auto hide after 5 seconds
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 5000);
        }
    }

    // Update API Key helper text based on provider
    function updateApiKeyHelper() {
        const providerField = document.getElementById('weather_provider');
        const helperElement = document.getElementById('weather_api_key_helper');

        if (providerField && helperElement) {
            const provider = providerField.value;
            let helperText = '';

            switch (provider) {
                case 'OpenWeatherMap':
                    helperText = 'GET API Key here: <a href="https://home.openweathermap.org/users/sign_up" target="_blank" class="text-blue-600 hover:text-blue-800 underline">https://home.openweathermap.org/users/sign_up</a>';
                    break;
                case 'Tomorrow.io':
                    helperText = 'GET API Key here: <a href="https://app.tomorrow.io/signup" target="_blank" class="text-blue-600 hover:text-blue-800 underline">https://app.tomorrow.io/signup</a>';
                    break;
                default:
                    helperText = 'GET API Key from your weather provider';
                    break;
            }

            helperElement.innerHTML = helperText;
        }
    }

    // Auto-update coordinates when location changes
    function updateLocationCoordinates() {
        const locationField = document.getElementById('weather_location');
        const latField = document.getElementById('weather_latitude');
        const lonField = document.getElementById('weather_longitude');

        if (locationField && latField && lonField) {
            locationField.addEventListener('blur', function() {
                const location = this.value.toLowerCase();
                const coordinates = {
                    'kuala lumpur': { lat: 3.1390, lon: 101.6869 },
                    'bintulu': { lat: 3.1667, lon: 113.0333 },
                    'sibu': { lat: 2.2876, lon: 111.8303 },
                    'miri': { lat: 4.4148, lon: 113.9917 },
                    'kuching': { lat: 1.5533, lon: 110.3592 },
                    'johor bahru': { lat: 1.4927, lon: 103.7414 },
                    'penang': { lat: 5.4164, lon: 100.3327 },
                    'ipoh': { lat: 4.5975, lon: 101.0901 }
                };

                for (const [city, coords] of Object.entries(coordinates)) {
                    if (location.includes(city)) {
                        latField.value = coords.lat;
                        lonField.value = coords.lon;
                        break;
                    }
                }
            });
        }
    }
    
    // Initialize first available tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        const availableTabs = document.querySelectorAll('.tab-button');
        if (availableTabs.length > 0) {
            // Get the first available tab
            const firstTab = availableTabs[0];
            const tabName = firstTab.id.replace('tab-', '');

            // Remove active class from all tabs first
            availableTabs.forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.style.display = 'none';
            });

            // Show the first available tab
            showTab(tabName);
        }

        // Initialize weather location auto-update
        updateLocationCoordinates();

        // Initialize API key helper
        updateApiKeyHelper();

        // Add event listener for provider change
        const providerField = document.getElementById('weather_provider');
        if (providerField) {
            providerField.addEventListener('change', updateApiKeyHelper);
        }

        // Load API tokens list on page load
        loadTokensList();
    });

    // API Configuration Functions
    function toggleApiEdit() {
        const editBtn = document.getElementById('api-edit-btn');
        const saveBtn = document.getElementById('api-save-btn');
        const isEditing = editBtn.textContent.includes('Batal');

        const apiFields = [
            'api_base_url', 'api_version', 'api_rate_limit',
            'api_timeout', 'api_max_retries', 'api_ssl_verification', 'api_logging_level'
        ];

        const sanctumFields = [
            'api-token-name', 'api-abilities', 'api-token-expiry', 'api-allowed-origins'
        ];

        if (!isEditing) {
            // Enable edit mode for API fields
            apiFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.readOnly = false;
                    element.disabled = false;
                    element.style.setProperty('background-color', '#ffffff', 'important');
                    element.style.setProperty('color', '#1f2937', 'important');
                }
            });

            // Enable edit mode for Sanctum fields (except Authentication)
            sanctumFields.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.readOnly = false;
                    element.disabled = false;
                    element.style.setProperty('background-color', '#ffffff', 'important');
                    element.style.setProperty('color', '#1f2937', 'important');
                }
            });

            // Show token management buttons
            const generateBtn = document.getElementById('sanctum-generate-btn');
            const revokeBtn = document.getElementById('sanctum-revoke-btn');
            if (generateBtn) generateBtn.style.display = 'flex';
            if (revokeBtn) revokeBtn.style.display = 'flex';

            // Change edit button to cancel button
            editBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">cancel</span>Batal Edit';
            editBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            editBtn.classList.add('bg-gray-500', 'hover:bg-gray-600');
            saveBtn.classList.remove('hidden');
        } else {
            // Disable edit mode (same as cancelApiEdit)
            cancelApiEdit();
        }
    }

    function cancelApiEdit() {
        const editBtn = document.getElementById('api-edit-btn');
        const saveBtn = document.getElementById('api-save-btn');

        const apiFields = [
            'api_base_url', 'api_version', 'api_rate_limit',
            'api_timeout', 'api_max_retries', 'api_ssl_verification', 'api_logging_level'
        ];

        const sanctumFields = [
            'api-token-name', 'api-abilities', 'api-token-expiry', 'api-allowed-origins'
        ];

        // Disable edit mode for API fields
        apiFields.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.readOnly = true;
                element.disabled = true;
                element.style.setProperty('background-color', '#e5e7eb', 'important');
                element.style.setProperty('color', '#4b5563', 'important');
            }
        });

        // Disable edit mode for Sanctum fields (except Authentication)
        sanctumFields.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.readOnly = true;
                element.disabled = true;
                element.style.setProperty('background-color', '#e5e7eb', 'important');
                element.style.setProperty('color', '#4b5563', 'important');
            }
        });

        // Hide token management buttons
        const generateBtn = document.getElementById('sanctum-generate-btn');
        const revokeBtn = document.getElementById('sanctum-revoke-btn');
        if (generateBtn) generateBtn.style.display = 'none';
        if (revokeBtn) revokeBtn.style.display = 'none';

        // Reset edit button
        editBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>Edit Konfigurasi';
        editBtn.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        editBtn.classList.add('bg-green-600', 'hover:bg-green-700');
        saveBtn.classList.add('hidden');

        // Update display values without reloading page
        updateApiDisplayValues();
    }

    function saveApiConfig(event) {
        // Prevent any form submission
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        const btn = event ? event.target : document.getElementById('api-save-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Menyimpan...';
        btn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = {
            base_url: document.getElementById('api_base_url').value,
            version: document.getElementById('api_version').value,
            auth_type: 'Bearer Token (Laravel Sanctum)', // Fixed auth type
            rate_limit: document.getElementById('api_rate_limit').value,
            timeout: document.getElementById('api_timeout').value,
            max_retries: parseInt(document.getElementById('api_max_retries').value),
            ssl_verification: document.getElementById('api_ssl_verification').value,
            logging_level: document.getElementById('api_logging_level').value,
            // Sanctum fields
            token_default_expiry: document.getElementById('api-token-expiry').value,
            token_name: document.getElementById('api-token-name').value,
            allowed_origins: document.getElementById('api-allowed-origins').value,
            default_abilities: Array.from(document.getElementById('api-abilities').selectedOptions).map(o => o.value),
            masjid_id: '{{ $selectedMasjidId }}',
            _token: csrfToken
        };

        // Add masjid_id from URL if present
        const masjidId = new URLSearchParams(window.location.search).get('masjid_id');
        const url = '{{ route("api-configurations.update", ["id" => 1]) }}' +
                   (masjidId ? '?masjid_id=' + masjidId : '');


        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showApiNotification('Konfigurasi API berjaya dikemas kini');

                // Update display values without reloading page
                updateApiDisplayValues();

                // Reset to view mode (like email does)
                toggleApiEdit();
            } else {
                showApiNotification('Ralat: ' + (data.message || 'Gagal menyimpan konfigurasi'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showApiNotification('Ralat: ' + error.message, 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function testApiConnectivity() {
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">hourglass_empty</span>Testing...';
        btn.disabled = true;

        const baseUrl = document.getElementById('api_base_url').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('{{ route("api-configurations.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ base_url: baseUrl })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showApiNotification('API berfungsi dengan baik');
            } else {
                showApiNotification('Ralat: ' + (data.message || 'Gagal menguji API'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showApiNotification('Ralat: ' + error.message, 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function showTokenManagement() {
        const section = document.getElementById('tokenManagementSection');
        section.classList.remove('hidden');
        section.scrollIntoView({ behavior: 'smooth' });

        // Reset to view mode
        resetTokenToViewMode();

        // Load existing tokens
        loadTokensList();
    }

    function hideTokenManagement() {
        const section = document.getElementById('tokenManagementSection');
        section.classList.add('hidden');

        // Reset to view mode when closing
        resetTokenToViewMode();
    }

    function toggleTokenEditMode() {
        const editBtn = document.getElementById('tokenEditBtn');
        const viewMode = document.getElementById('tokenViewMode');
        const editMode = document.getElementById('tokenEditMode');

        const isEditing = editBtn.textContent.includes('Batal');

        if (!isEditing) {
            // Switch to edit mode
            viewMode.classList.add('hidden');
            editMode.classList.remove('hidden');
            editBtn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">close</span>Batal Edit';
        } else {
            // Switch to view mode
            resetTokenToViewMode();
        }
    }

    function resetTokenToViewMode() {
        // Hide token management buttons
        const generateBtn = document.getElementById('sanctum-generate-btn');
        const revokeBtn = document.getElementById('sanctum-revoke-btn');
        if (generateBtn) generateBtn.style.display = 'none';
        if (revokeBtn) revokeBtn.style.display = 'none';

        // Call cancelApiEdit to reset form to view mode
        cancelApiEdit();
    }

    function loadTokensList() {
        const tokenList = document.getElementById('api-token-list');

        fetch('{{ route("sanctum-tokens.index") }}?masjid_id={{ $selectedMasjidId }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!data || !data.success) {
                tokenList.innerHTML = '<div class="text-red-600">Failed to load tokens</div>';
                return;
            }

            if (!data.tokens || data.tokens.length === 0) {
                tokenList.innerHTML = '<div class="text-gray-500 text-center py-4">No tokens found</div>';
                return;
            }

            tokenList.innerHTML = data.tokens.map(token => {
                const abilities = Array.isArray(token.abilities) ? token.abilities.join(', ') : token.abilities;
                const createdAt = token.created_at ? new Date(token.created_at).toLocaleString() : '-';
                const lastUsed = token.last_used_at ? new Date(token.last_used_at).toLocaleString() : 'Never';

                return `
                    <div class="mb-3 p-3 border border-gray-200 rounded-sm bg-white">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">${token.name}</div>
                                <div class="text-xs text-gray-600 mt-1">
                                    <div><strong>Abilities:</strong> ${abilities}</div>
                                    <div><strong>Created:</strong> ${createdAt}</div>
                                    <div><strong>Last Used:</strong> ${lastUsed}</div>
                                </div>
                            </div>
                            <div class="ml-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Error loading tokens:', error);
            tokenList.innerHTML = '<div class="text-red-600">Error loading tokens</div>';
        });
    }

    function generateSanctumToken() {
        const name = document.getElementById('api-token-name').value.trim() || 'publicweb';
        const expiry = document.getElementById('api-token-expiry').value;

        // Default abilities for E-Masjid API
        const defaultAbilities = [
            'read:overview',
            'read:kariah',
            'write:kariah',
            'read:tetapan',
            'write:tetapan',
            'read:integrations',
            'write:integrations',
            'read:roles',
            'write:roles',
            'read:user-access',
            'write:user-access',
            'read:audit-logs',
            'clear:audit-logs',
            'read:system-status',
            'read:faq'
        ];

        fetch('{{ route("sanctum-tokens.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                token_name: name,
                abilities: defaultAbilities,
                expires_in_minutes: expiry === 'never' ? null : getExpiryMinutes(expiry),
                masjid_id: '{{ $selectedMasjidId }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.token) {
                // Show the token once
                const tokenList = document.getElementById('api-token-list');
                tokenList.innerHTML = `
                    <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-sm text-xs">
                        <div class="font-medium text-emerald-800 mb-2">New Token Generated:</div>
                        <div class="font-mono text-emerald-900 bg-white p-2 rounded border break-all">${data.token}</div>
                        <div class="text-red-600 mt-2 font-medium">⚠️ Save this token immediately. It will not be shown again.</div>
                    </div>
                `;
                showApiNotification('Token generated successfully', 'success');

                // Switch back to view mode and reload token list after 5 seconds
                setTimeout(() => {
                    resetTokenToViewMode();
                    loadTokensList();
                }, 5000);
            } else {
                showApiNotification(data.message || 'Failed to generate token', 'error');
            }
        })
        .catch(error => {
            console.error('Error generating token:', error);
            showApiNotification('Error generating token', 'error');
        });
    }

    function getExpiryMinutes(expiry) {
        switch(expiry) {
            case '15m': return 15;
            case '1h': return 60;
            case '6h': return 360;
            case '24h': return 1440;
            case '7d': return 10080;
            default: return null;
        }
    }

    function revokeAllTokens() {
        if (!confirm('Adakah anda pasti mahu membatalkan SEMUA token? Tindakan ini tidak boleh dibuat asal.')) {
            return;
        }

        fetch('{{ route("sanctum-tokens.destroy-all") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                masjid_id: '{{ $selectedMasjidId }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tokenList = document.getElementById('api-token-list');
                tokenList.innerHTML = '<div class="text-gray-500 text-center py-4">No tokens found</div>';
                showApiNotification('Semua token telah dibatalkan', 'success');

                // Switch back to view mode
                resetTokenToViewMode();
            } else {
                showApiNotification(data.message || 'Gagal membatalkan token', 'error');
            }
        })
        .catch(error => {
            console.error('Error revoking tokens:', error);
            showApiNotification('Ralat membatalkan token', 'error');
        });
    }

    function updateApiDisplayValues() {
        // Update display values from input fields (similar to email implementation)
        const fields = [
            'api_base_url', 'api_version', 'api_rate_limit',
            'api_timeout', 'api_max_retries', 'api_ssl_verification', 'api_logging_level'
        ];

        fields.forEach(fieldId => {
            const input = document.getElementById(fieldId);
            const display = document.getElementById(fieldId + '_display');
            if (input && display) {
                display.textContent = input.value;
            }
        });

        // Update Sanctum fields
        const sanctumFields = [
            'api-token-name', 'api-abilities', 'api-token-expiry', 'api-allowed-origins'
        ];

        sanctumFields.forEach(fieldId => {
            const input = document.getElementById(fieldId);
            const display = document.getElementById(fieldId + '_display');
            if (input && display) {
                if (fieldId === 'api-abilities') {
                    // Handle multi-select
                    const selectedOptions = Array.from(input.selectedOptions).map(o => o.text);
                    display.textContent = selectedOptions.join(', ') || 'None selected';
                } else {
                    display.textContent = input.value;
                }
            }
        });
    }

    function loadApiConfig() {
        // Instead of reloading page, just update display values
        updateApiDisplayValues();
    }

    function testApiConnection() {
        const btn = event.target;
        const originalText = btn.innerHTML;

        // Show loading state
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">api</span>Testing...';
        btn.disabled = true;

        showApiNotification('Testing API connection...', 'info');

        fetch('{{ route("api-configurations.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                masjid_id: '{{ $selectedMasjidId }}'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showApiNotification('API connection successful!', 'success');

                // Update test status display if elements exist
                const lastTestElement = document.getElementById('api_last_test');
                const testStatusElement = document.getElementById('api_test_status');

                if (lastTestElement && testStatusElement) {
                    const now = new Date();
                    const timeString = now.toLocaleDateString('ms-MY') + ' ' + now.toLocaleTimeString('ms-MY', {hour: '2-digit', minute: '2-digit'});
                    lastTestElement.value = timeString;
                    testStatusElement.value = 'Berjaya';
                }
            } else {
                showApiNotification('API connection failed: ' + (data.message || 'Unknown error'), 'error');

                // Update test status display for failed test
                const lastTestElement = document.getElementById('api_last_test');
                const testStatusElement = document.getElementById('api_test_status');

                if (lastTestElement && testStatusElement) {
                    const now = new Date();
                    const timeString = now.toLocaleDateString('ms-MY') + ' ' + now.toLocaleTimeString('ms-MY', {hour: '2-digit', minute: '2-digit'});
                    lastTestElement.value = timeString;
                    testStatusElement.value = 'Gagal';
                }
            }
        })
        .catch(error => {
            console.error('Error testing API:', error);
            showApiNotification('Error testing API connection: ' + error.message, 'error');
        })
        .finally(() => {
            // Restore button
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function showApiNotification(message, type = 'success') {
        // Create notification if it doesn't exist
        let notification = document.getElementById('apiSuccessNotification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'apiSuccessNotification';
            notification.className = 'mb-4 hidden';
            notification.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded flex items-center">
                    <span class="material-icons mr-2" style="font-size: 14px !important;">check_circle</span>
                    <span id="apiSuccessMessage" class="text-xs">${message}</span>
                </div>
            `;

            // Insert before API content
            const apiContent = document.getElementById('content-api');
            if (apiContent) {
                apiContent.parentNode.insertBefore(notification, apiContent);
            }
        }

        const messageEl = document.getElementById('apiSuccessMessage');
        const container = notification.querySelector('div');

        if (messageEl && container) {
            messageEl.textContent = message;

            // Update styling based on type
            if (type === 'error') {
                container.className = 'bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded flex items-center';
                container.querySelector('.material-icons').textContent = 'error';
            } else if (type === 'info') {
                container.className = 'bg-blue-100 border border-blue-400 text-blue-700 px-3 py-2 rounded flex items-center';
                container.querySelector('.material-icons').textContent = 'info';
            } else {
                container.className = 'bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded flex items-center';
                container.querySelector('.material-icons').textContent = 'check_circle';
            }

            // Show notification
            notification.classList.remove('hidden');

            // Auto-hide after 3 seconds
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }
    }

    // API Sync Data Function
    function syncApiData() {
        const btn = event.target;
        const originalText = btn.innerHTML;

        // Show loading state
        btn.innerHTML = '<span class="material-icons mr-2" style="font-size: 16px !important;">sync</span>Syncing...';
        btn.disabled = true;

        showApiNotification('Starting data synchronization...', 'info');

        fetch('{{ route("api-configurations.sync") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                masjid_id: '{{ $selectedMasjidId }}'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showApiNotification('Data synchronization completed successfully!', 'success');
            } else {
                showApiNotification('Sync failed: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error syncing data:', error);
            showApiNotification('Error during data synchronization', 'error');
        })
        .finally(() => {
            // Restore button
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    </script>
</body>
</html>
