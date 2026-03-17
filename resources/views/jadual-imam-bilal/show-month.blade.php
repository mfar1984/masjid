<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Imam & Bilal - {{ $namaBulan }} - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .calendar-table { width: 100%; border-collapse: collapse; font-size: 11px; }
        .calendar-table th { background: linear-gradient(180deg, #1a5f2a 0%, #145022 100%); color: white; padding: 10px 6px; text-align: center; font-weight: bold; border: 1px solid #145022; }
        .calendar-table td { border: 1px solid #ddd; padding: 6px 4px; vertical-align: middle; height: 55px; text-align: center; }
        .calendar-table tbody tr:nth-child(odd) { background: #fafafa; }
        .calendar-table tbody tr:nth-child(even) { background: #ffffff; }
        .tarikh-cell { background: linear-gradient(135deg, #e8f5ed 0%, #c8e6c9 100%) !important; font-weight: bold; text-align: left !important; padding-left: 10px !important; }
        .tarikh-cell .date-masehi { font-size: 12px; color: #1a5f2a; font-weight: bold; }
        .tarikh-cell .date-hijri { font-size: 9px; color: #666; font-style: italic; }
        .tarikh-cell .day-name { font-size: 9px; color: #555; }
        .friday-row .tarikh-cell { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important; }
        .friday-row .tarikh-cell .day-name { color: #d97706; font-weight: bold; }
        .cell-content { display: flex; flex-direction: column; gap: 3px; align-items: center; }
        .person-row { display: flex; align-items: center; gap: 4px; justify-content: center; }
        .color-dot { width: 10px; height: 10px; flex-shrink: 0; border: 1px solid rgba(0,0,0,0.1); }
        .color-dot.imam { border-radius: 50%; }
        .color-dot.bilal { border-radius: 2px; }
        .person-name { font-size: 9px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80px; }
        .imam-name { font-weight: bold; color: #1a5f2a; }
        .bilal-name { color: #555; }
        .empty-cell { color: #ccc; font-style: italic; }
        .status-batal { text-decoration: line-through; color: #ef4444 !important; }
        .status-ganti { color: #f97316 !important; }
        .legend-section { display: flex; gap: 30px; padding: 12px 15px; background: linear-gradient(135deg, #f0f9f4 0%, #e8f5ed 100%); border-radius: 6px; border: 1px solid #c8e6c9; }
        .legend-group { flex: 1; }
        .legend-title { font-weight: bold; font-size: 11px; margin-bottom: 6px; color: #1a5f2a; border-bottom: 2px solid #1a5f2a; padding-bottom: 4px; display: inline-block; }
        .legend-items { display: flex; flex-wrap: wrap; gap: 8px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: 10px; background: white; padding: 3px 6px; border-radius: 4px; border: 1px solid #e0e0e0; }
        .legend-color { width: 12px; height: 12px; display: inline-block; border: 1px solid rgba(0,0,0,0.1); }
        .legend-color.imam { border-radius: 50%; }
        .legend-color.bilal { border-radius: 3px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <div class="flex items-center mb-2">
                            <a href="{{ route('jadual-imam-bilal.index') }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 20px !important;">arrow_back</span>
                            </a>
                            <h1 class="text-xl font-bold text-gray-900">Jadual Imam & Bilal</h1>
                        </div>
                        <p class="text-xs text-gray-600">{{ $namaBulan }} - {{ $masjid->nama ?? 'E-Masjid' }}</p>
                        @php
                            $startDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
                            $endDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
                            // Use pattern for proper Hijri month format
                            $hijriFormatter = \IntlDateFormatter::create(
                                'ms_MY@calendar=islamic', 
                                \IntlDateFormatter::NONE, 
                                \IntlDateFormatter::NONE, 
                                null, 
                                \IntlCalendar::createInstance(null, '@calendar=islamic'), 
                                'MMMM y'
                            );
                            $hijriStart = $hijriFormatter ? $hijriFormatter->format($startDate) . 'H' : '';
                            $hijriEnd = $hijriFormatter ? $hijriFormatter->format($endDate) . 'H' : '';
                            $hijriMonth = ($hijriStart === $hijriEnd) ? $hijriStart : $hijriStart . ' - ' . $hijriEnd;
                        @endphp
                        @if($hijriMonth)
                            <p class="text-xs text-green-600 italic">{{ $hijriMonth }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('jadual-imam-bilal.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700" target="_blank">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">print</span>
                            Cetak Jadual
                        </a>
                        @if(auth()->user()->hasPermission('jadual_imam_bilal', 'create'))
                            <a href="{{ route('jadual-imam-bilal.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Jadual
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-sm text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Legend Section -->
                @if(isset($calendarData) && (count($calendarData['legend']['imam']) > 0 || count($calendarData['legend']['bilal']) > 0))
                <div class="legend-section mb-4">
                    <div class="legend-group">
                        <div class="legend-title">● SENARAI IMAM</div>
                        <div class="legend-items">
                            @forelse($calendarData['legend']['imam'] as $name => $color)
                                <div class="legend-item">
                                    <span class="legend-color imam" style="background-color: {{ $color }};"></span>
                                    <span>{{ $name }}</span>
                                </div>
                            @empty
                                <span style="color: #999;">Tiada data</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="legend-group">
                        <div class="legend-title">■ SENARAI BILAL</div>
                        <div class="legend-items">
                            @forelse($calendarData['legend']['bilal'] as $name => $color)
                                <div class="legend-item">
                                    <span class="legend-color bilal" style="background-color: {{ $color }};"></span>
                                    <span>{{ $name }}</span>
                                </div>
                            @empty
                                <span style="color: #999;">Tiada data</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif

                <!-- Calendar Grid -->
                @if(isset($calendarData))
                <div class="overflow-x-auto">
                    <table class="calendar-table">
                        <thead>
                            <tr>
                                <th style="width: 130px;">Tarikh</th>
                                @foreach($calendarData['waktuSolat'] as $waktu)
                                    <th>{{ $waktu }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $hariMelayu = [
                                    'Sunday' => 'Ahad', 'Monday' => 'Isnin', 'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu', 'Thursday' => 'Khamis', 'Friday' => 'Jumaat', 'Saturday' => 'Sabtu'
                                ];
                            @endphp
                            @foreach($calendarData['schedules'] as $day => $dayData)
                                @php
                                    $currentDate = \Carbon\Carbon::createFromDate($tahun, $bulan, $day);
                                    $dayEnglish = $currentDate->format('l');
                                    $dayMalay = $hariMelayu[$dayEnglish] ?? $dayEnglish;
                                    // Use pattern for proper Hijri date format
                                    $hijriFormatter = \IntlDateFormatter::create(
                                        'ms_MY@calendar=islamic', 
                                        \IntlDateFormatter::NONE, 
                                        \IntlDateFormatter::NONE, 
                                        null, 
                                        \IntlCalendar::createInstance(null, '@calendar=islamic'), 
                                        'd MMM y'
                                    );
                                    $hijriDate = $hijriFormatter ? $hijriFormatter->format($currentDate) . 'H' : '';
                                @endphp
                                <tr class="{{ $dayData['isFriday'] ? 'friday-row' : '' }}">
                                    <td class="tarikh-cell">
                                        <div class="date-masehi">{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}/{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}</div>
                                        @if($hijriDate)
                                            <div class="date-hijri">{{ $hijriDate }}</div>
                                        @endif
                                        <div class="day-name">{{ $dayMalay }}</div>
                                    </td>
                                    @foreach($calendarData['waktuSolat'] as $waktu)
                                        <td>
                                            @if(isset($dayData['waktu'][$waktu]) && $dayData['waktu'][$waktu])
                                                @php
                                                    $schedule = $dayData['waktu'][$waktu];
                                                    // Use short name for color lookup in legend
                                                    $imamShort = $schedule['imam_short'] ?? $schedule['imam'];
                                                    $bilalShort = $schedule['bilal_short'] ?? $schedule['bilal'];
                                                    $imamColor = $calendarData['legend']['imam'][$imamShort] ?? '#999';
                                                    $bilalColor = $calendarData['legend']['bilal'][$bilalShort] ?? '#999';
                                                @endphp
                                                <div class="cell-content">
                                                    <div class="person-row">
                                                        <span class="color-dot imam" style="background-color: {{ $imamColor }};"></span>
                                                        <span class="person-name imam-name {{ $schedule['status_imam'] === 'Batal' ? 'status-batal' : ($schedule['status_imam'] === 'Ganti' ? 'status-ganti' : '') }}">
                                                            {{ $schedule['imam'] }}
                                                        </span>
                                                    </div>
                                                    <div class="person-row">
                                                        <span class="color-dot bilal" style="background-color: {{ $bilalColor }};"></span>
                                                        <span class="person-name bilal-name {{ $schedule['status_bilal'] === 'Batal' ? 'status-batal' : ($schedule['status_bilal'] === 'Ganti' ? 'status-ganti' : '') }}">
                                                            {{ $schedule['bilal'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="empty-cell">-</div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if($jadualList->count() == 0)
                    <div class="text-center py-8 bg-gray-50 rounded-lg mt-4">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">event_note</span>
                        <p class="text-sm text-gray-500">Tiada jadual untuk bulan ini</p>
                        <p class="text-xs text-gray-400 mt-2">Gunakan "Auto-Generate" untuk menjana jadual</p>
                        <a href="{{ route('jadual-imam-bilal.auto-generate') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-purple-600 text-white text-xs rounded hover:bg-purple-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">autorenew</span>
                            Auto-Generate Jadual
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
