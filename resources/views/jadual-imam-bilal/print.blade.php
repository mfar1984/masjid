<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Imam & Bilal - {{ $namaBulan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
            background: #fff;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #1a5f2a;
        }
        .header h1 {
            font-size: 22px;
            color: #1a5f2a;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .header .bulan-info {
            font-size: 14px;
            color: #555;
        }
        .header .hijri-info {
            font-size: 12px;
            color: #1a5f2a;
            font-style: italic;
            margin-top: 3px;
        }
        
        /* Legend Section */
        .legend-section {
            display: flex;
            gap: 40px;
            margin-bottom: 20px;
            padding: 15px 20px;
            background: linear-gradient(135deg, #f0f9f4 0%, #e8f5ed 100%);
            border-radius: 8px;
            border: 1px solid #c8e6c9;
        }
        .legend-group {
            flex: 1;
        }
        .legend-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            color: #1a5f2a;
            border-bottom: 2px solid #1a5f2a;
            padding-bottom: 5px;
            display: inline-block;
        }
        .legend-items {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        .legend-color {
            width: 14px;
            height: 14px;
            display: inline-block;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .legend-color.imam {
            border-radius: 50%;
        }
        .legend-color.bilal {
            border-radius: 3px;
        }
        .legend-name {
            font-size: 10px;
            font-weight: 500;
        }
        
        /* Calendar Table */
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .calendar-table th {
            background: linear-gradient(180deg, #1a5f2a 0%, #145022 100%);
            color: white;
            padding: 10px 6px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #145022;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .calendar-table th.tarikh-header {
            width: 140px;
            text-align: center;
        }
        .calendar-table th.waktu-header {
            width: calc((100% - 140px) / 5);
        }
        .calendar-table td {
            border: 1px solid #ddd;
            padding: 6px 4px;
            vertical-align: middle;
            height: 50px;
            text-align: center;
        }
        .calendar-table tbody tr:nth-child(odd) {
            background: #fafafa;
        }
        .calendar-table tbody tr:nth-child(even) {
            background: #ffffff;
        }
        .calendar-table tbody tr:hover {
            background: #f5f5f5;
        }
        
        /* Tarikh Cell */
        .tarikh-cell {
            background: linear-gradient(135deg, #e8f5ed 0%, #c8e6c9 100%) !important;
            font-weight: bold;
            text-align: left !important;
            padding-left: 10px !important;
        }
        .tarikh-cell .date-masehi {
            font-size: 13px;
            color: #1a5f2a;
            font-weight: bold;
        }
        .tarikh-cell .date-hijri {
            font-size: 9px;
            color: #666;
            font-style: italic;
        }
        .tarikh-cell .day-name {
            font-size: 9px;
            color: #555;
            font-weight: normal;
            margin-top: 2px;
        }
        
        /* Friday Row */
        .friday-row .tarikh-cell {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
        }
        .friday-row .tarikh-cell .day-name {
            color: #d97706;
            font-weight: bold;
        }
        .friday-row .tarikh-cell .date-masehi {
            color: #b45309;
        }
        
        /* Cell Content */
        .cell-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: center;
        }
        .person-row {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: center;
        }
        .color-dot {
            width: 10px;
            height: 10px;
            flex-shrink: 0;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .color-dot.imam {
            border-radius: 50%;
        }
        .color-dot.bilal {
            border-radius: 2px;
        }
        .person-name {
            font-size: 9px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 90px;
        }
        .imam-name {
            font-weight: bold;
            color: #1a5f2a;
        }
        .bilal-name {
            color: #555;
        }
        .empty-cell {
            color: #ccc;
            font-style: italic;
            font-size: 10px;
        }
        
        /* Status indicators */
        .status-batal { 
            text-decoration: line-through; 
            color: #ef4444 !important; 
        }
        .status-ganti { 
            color: #f97316 !important; 
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #1a5f2a;
            font-size: 9px;
            color: #666;
            text-align: center;
            display: flex;
            justify-content: space-between;
        }
        .footer-left {
            text-align: left;
        }
        .footer-right {
            text-align: right;
        }
        
        /* Buttons */
        .action-buttons {
            position: fixed;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-print {
            background: linear-gradient(180deg, #1a5f2a 0%, #145022 100%);
            color: white;
        }
        .btn-print:hover {
            background: linear-gradient(180deg, #145022 0%, #0d3a17 100%);
        }
        .btn-back {
            background: linear-gradient(180deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }
        .btn-back:hover {
            background: linear-gradient(180deg, #4b5563 0%, #374151 100%);
        }
        
        .no-data-message {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
            background: #f9f9f9;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        /* Print Styles - A3 Landscape */
        @media print {
            .action-buttons {
                display: none !important;
            }
            body {
                padding: 0;
                font-size: 10px;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page {
                size: A3 landscape;
                margin: 15mm;
            }
            .header {
                margin-bottom: 15px;
            }
            .legend-section {
                page-break-inside: avoid;
                margin-bottom: 15px;
            }
            .calendar-table {
                font-size: 9px;
            }
            .calendar-table th {
                padding: 8px 4px;
                font-size: 10px;
            }
            .calendar-table td {
                height: 45px;
                padding: 4px;
            }
            .tarikh-cell .date-masehi {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="action-buttons">
        <a href="{{ route('jadual-imam-bilal.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-back">← Kembali</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Jadual</button>
    </div>

    <div class="header">
        <h1>{{ $masjid->nama ?? 'E-Masjid' }}</h1>
        <h2>Jadual Tugas Imam & Bilal</h2>
        <div class="bulan-info">{{ $namaBulan }}</div>
        @php
            // Calculate Hijri month with proper format
            $startDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
            $endDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
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
            <div class="hijri-info">{{ $hijriMonth }}</div>
        @endif
    </div>

    @if(isset($calendarData))
        <!-- Legend Section -->
        @if(count($calendarData['legend']['imam']) > 0 || count($calendarData['legend']['bilal']) > 0)
        <div class="legend-section">
            <div class="legend-group">
                <div class="legend-title">● SENARAI IMAM</div>
                <div class="legend-items">
                    @forelse($calendarData['legend']['imam'] as $name => $color)
                        <div class="legend-item">
                            <span class="legend-color imam" style="background-color: {{ $color }};"></span>
                            <span class="legend-name">{{ $name }}</span>
                        </div>
                    @empty
                        <span class="legend-name" style="color: #999;">Tiada data</span>
                    @endforelse
                </div>
            </div>
            <div class="legend-group">
                <div class="legend-title">■ SENARAI BILAL</div>
                <div class="legend-items">
                    @forelse($calendarData['legend']['bilal'] as $name => $color)
                        <div class="legend-item">
                            <span class="legend-color bilal" style="background-color: {{ $color }};"></span>
                            <span class="legend-name">{{ $name }}</span>
                        </div>
                    @empty
                        <span class="legend-name" style="color: #999;">Tiada data</span>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        <!-- Calendar Grid -->
        <table class="calendar-table">
            <thead>
                <tr>
                    <th class="tarikh-header">Tarikh</th>
                    @foreach($calendarData['waktuSolat'] as $waktu)
                        <th class="waktu-header">{{ $waktu }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $hariMelayu = [
                        'Sunday' => 'Ahad',
                        'Monday' => 'Isnin', 
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Khamis',
                        'Friday' => 'Jumaat',
                        'Saturday' => 'Sabtu'
                    ];
                @endphp
                @foreach($calendarData['schedules'] as $day => $dayData)
                    @php
                        $currentDate = \Carbon\Carbon::createFromDate($tahun, $bulan, $day);
                        $dayEnglish = $currentDate->format('l');
                        $dayMalay = $hariMelayu[$dayEnglish] ?? $dayEnglish;
                        
                        // Get Hijri date with proper format
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
        
        @if($jadualList->count() == 0)
            <div class="no-data-message">
                ⚠️ Tiada jadual dijana untuk bulan ini. Sila gunakan fungsi "Auto-Generate" untuk menjana jadual.
            </div>
        @endif
    @else
        <div class="no-data-message">
            Tiada data kalendar untuk bulan ini.
        </div>
    @endif

    <div class="footer">
        <div class="footer-left">
            <strong>{{ $masjid->nama ?? 'E-Masjid' }}</strong><br>
            {{ $masjid->alamat ?? '' }}
        </div>
        <div class="footer-right">
            Dijana pada: {{ now()->format('d/m/Y H:i') }}<br>
            E-Masjid System v3.0
        </div>
    </div>
</body>
</html>
