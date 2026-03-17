<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta Organisasi - {{ $masjid->nama ?? 'E-Masjid' }}</title>
    <x-favicon />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A3 landscape;
            margin: 10mm;
        }
        
        @media print {
            body { 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print { display: none !important; }
            .print-container { 
                width: 400mm !important;
                min-height: 277mm !important;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
        }
        
        /* Header Bar */
        .header-bar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .header-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
        
        .header-subtitle {
            font-size: 12px;
            color: #4b5563;
            margin: 0;
        }
        
        .header-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            height: 32px;
            padding: 4px 16px;
            font-size: 12px;
            border-radius: 4px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-gray {
            background: #e5e7eb;
            color: #374151;
        }
        
        .btn-gray:hover {
            background: #d1d5db;
        }
        
        .btn-blue {
            background: #2563eb;
            color: white;
        }
        
        .btn-blue:hover {
            background: #1d4ed8;
        }
        
        .btn .material-icons {
            font-size: 14px;
            margin-right: 4px;
        }
        
        /* Print Container */
        .print-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: white;
            min-height: 100vh;
            overflow-x: auto;
        }
        
        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 24px;
            padding-top: 16px;
        }
        
        .page-header h1 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px 0;
        }
        
        .page-header h2 {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }
        
        .page-header p {
            font-size: 12px;
            color: #6b7280;
            margin: 4px 0 0 0;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 0;
        }
        
        .empty-state .material-icons {
            font-size: 64px;
            color: #d1d5db;
            margin-bottom: 16px;
        }
        
        .empty-state p {
            font-size: 14px;
            color: #6b7280;
        }
        
        /* Footer */
        .page-footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }

        /* Organization Chart */
        .org-chart {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            width: 100%;
        }
        
        /* Level group */
        .level-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            position: relative;
        }
        
        /* Vertical connector from parent */
        .connector-down {
            width: 2px;
            height: 30px;
            background: #374151;
        }
        
        /* Horizontal connector line */
        .connector-horizontal {
            height: 2px;
            background: #374151;
            position: absolute;
            top: 0;
        }
        
        /* Nodes row */
        .nodes-row {
            display: flex;
            justify-content: center;
            position: relative;
            padding-top: 30px;
        }
        
        .nodes-row.first-level {
            padding-top: 0;
        }
        
        /* Single node - no padding needed, connector-down handles it */
        .nodes-row.single-node {
            padding-top: 0;
        }
        
        /* Node wrapper */
        .node-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 10px;
            position: relative;
        }
        
        /* Vertical drop line */
        .node-wrapper .drop-line {
            width: 2px;
            height: 30px;
            background: #374151;
            position: absolute;
            top: -30px;
        }
        
        /* Hide drop line for first level and single node */
        .nodes-row.first-level .node-wrapper .drop-line {
            display: none;
        }
        
        .nodes-row.single-node .node-wrapper .drop-line {
            display: none;
        }

        /* Node Card */
        .node-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: default;
        }
        
        .avatar-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 3px solid #e25822;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: -8px;
            z-index: 10;
            position: relative;
        }
        
        .avatar-circle .material-icons {
            font-size: 24px;
            color: #666;
        }
        
        .card-body {
            min-width: 100px;
            max-width: 130px;
            text-align: center;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }
        
        .card-header {
            padding: 12px 5px 3px 5px;
            font-size: 7px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            color: white;
            line-height: 1.2;
        }
        
        .card-content {
            padding: 4px 5px 5px 5px;
            background: white;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        
        .card-name {
            font-size: 8px;
            font-weight: 500;
            color: #1f2937;
            line-height: 1.2;
        }
        
        /* Color Schemes */
        .level-1 .avatar-circle { border-color: #dc2626; }
        .level-1 .card-header { background: #dc2626; }
        
        .level-2 .avatar-circle { border-color: #ea580c; }
        .level-2 .card-header { background: #ea580c; }
        
        .level-3 .avatar-circle { border-color: #0d9488; }
        .level-3 .card-header { background: #0d9488; }
        
        .level-4 .avatar-circle { border-color: #0d9488; }
        .level-4 .card-header { background: #0d9488; }
        
        .level-5 .avatar-circle { border-color: #2563eb; }
        .level-5 .card-header { background: #2563eb; }
        
        .level-6 .avatar-circle { border-color: #6b7280; }
        .level-6 .card-header { background: #6b7280; }
        
        .level-7 .avatar-circle { border-color: #7c3aed; }
        .level-7 .card-header { background: #7c3aed; }
        
        .level-8 .avatar-circle { border-color: #a855f7; }
        .level-8 .card-header { background: #a855f7; }
        
        .level-9 .avatar-circle { border-color: #c026d3; }
        .level-9 .card-header { background: #c026d3; }
    </style>
</head>
<body>
    <!-- Screen Header - No Print -->
    <div class="header-bar no-print">
        <div class="header-container">
            <div>
                <h1 class="header-title">Carta Organisasi</h1>
                <p class="header-subtitle">{{ $masjid->nama ?? 'Masjid' }}</p>
            </div>
            <div class="header-buttons">
                <a href="{{ route('ajk.index') }}" class="btn btn-gray">
                    <span class="material-icons">arrow_back</span>
                    Kembali
                </a>
                <button onclick="window.print()" class="btn btn-blue">
                    <span class="material-icons">print</span>
                    Cetak A3
                </button>
            </div>
        </div>
    </div>

    <!-- Print Container -->
    <div class="print-container">
        <!-- Header -->
        <div class="page-header">
            <h1>CARTA ORGANISASI</h1>
            <h2>{{ strtoupper($masjid->nama ?? 'MASJID') }}</h2>
            <p>Sesi {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>

        @if($levels->isEmpty())
            <div class="empty-state">
                <span class="material-icons">people</span>
                <p>Tiada ahli jawatankuasa aktif dijumpai</p>
            </div>
        @else
            @php
                $sortedLevels = $levels->keys()->sort()->values()->toArray();
            @endphp
            
            <!-- Organization Chart -->
            <div class="org-chart">
                @foreach($sortedLevels as $index => $levelNum)
                    @php 
                        $members = $levels[$levelNum];
                        $memberCount = $members->count();
                        $isFirst = $index === 0;
                    @endphp
                    
                    <div class="level-group" data-level="{{ $levelNum }}">
                        {{-- Vertical connector from previous level --}}
                        @if(!$isFirst)
                            <div class="connector-down"></div>
                        @endif
                        
                        {{-- Nodes row --}}
                        <div class="nodes-row {{ $isFirst ? 'first-level' : '' }} {{ $memberCount === 1 ? 'single-node' : '' }}">
                            @foreach($members as $member)
                                <div class="node-wrapper">
                                    <div class="drop-line"></div>
                                    <div class="node-card level-{{ $levelNum }}">
                                        <div class="avatar-circle">
                                            @if($member->gambar_path)
                                                <img src="{{ Storage::url($member->gambar_path) }}" alt="{{ $member->nama }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            @else
                                                <span class="material-icons">person</span>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="card-header">{{ $member->jawatan }}</div>
                                            <div class="card-content">
                                                <div class="card-name">{{ $member->nama }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="page-footer">
                <p>Dijana oleh Sistem E-Masjid pada {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        @endif
    </div>

    <script>
        // Draw horizontal connector lines after DOM loads
        document.addEventListener('DOMContentLoaded', function() {
            const nodesRows = document.querySelectorAll('.nodes-row:not(.first-level):not(.single-node)');
            
            nodesRows.forEach(row => {
                const wrappers = row.querySelectorAll('.node-wrapper');
                if (wrappers.length < 2) return;
                
                const firstWrapper = wrappers[0];
                const lastWrapper = wrappers[wrappers.length - 1];
                
                // Get center positions of first and last nodes
                const rowRect = row.getBoundingClientRect();
                const firstRect = firstWrapper.getBoundingClientRect();
                const lastRect = lastWrapper.getBoundingClientRect();
                
                const firstCenter = firstRect.left + (firstRect.width / 2) - rowRect.left;
                const lastCenter = lastRect.left + (lastRect.width / 2) - rowRect.left;
                
                // Create horizontal line
                const hLine = document.createElement('div');
                hLine.className = 'connector-horizontal';
                hLine.style.left = firstCenter + 'px';
                hLine.style.width = (lastCenter - firstCenter) + 'px';
                row.appendChild(hLine);
            });
        });
    </script>
</body>
</html>
