<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetapan Asnaf - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tetapan Asnaf</h1>
                    <p class="text-xs text-gray-600">Konfigurasi sistem pengurusan zakat dan asnaf untuk {{ auth()->user()->masjid->nama ?? 'masjid anda' }}</p>
                </div>
            
                <!-- Tabs -->
                <div class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex flex-col sm:flex-row space-y-2 sm:space-y-0" style="gap: 28px !important;">
                            @if($tabPermissions['had_kifayah'])
                            <button onclick="showTab('had-kifayah')" id="tab-had-kifayah" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-xs text-blue-600 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">account_balance_wallet</span>
                                Had Kifayah
                            </button>
                            @endif

                            @if($tabPermissions['had_bantuan'])
                            <button onclick="showTab('had-bantuan')" id="tab-had-bantuan" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">volunteer_activism</span>
                                Had Bantuan
                            </button>
                            @endif
                            
                            @if($tabPermissions['workflow'])
                            <button onclick="showTab('workflow')" id="tab-workflow" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">approval</span>
                                Workflow
                            </button>
                            @endif

                            @if($tabPermissions['permohonan'])
                            <button onclick="showTab('permohonan')" id="tab-permohonan" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">description</span>
                                Permohonan
                            </button>
                            @endif

                            @if($tabPermissions['kategori'])
                            <button onclick="showTab('kategori-data')" id="tab-kategori-data" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">category</span>
                                Kategori
                            </button>
                            @endif

                            @if($tabPermissions['payment'])
                            <button onclick="showTab('payment')" id="tab-payment" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">payment</span>
                                Payment Gateway
                            </button>
                            @endif

                            @if($tabPermissions['display'])
                            <button onclick="showTab('display')" id="tab-display" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs text-gray-500 flex items-center justify-center sm:justify-start">
                                <span class="material-icons mr-3" style="font-size: 16px !important;">display_settings</span>
                                Display
                            </button>
                            @endif
                        </nav>
                    </div>
                </div>

                <!-- Tab Contents -->
                @if($tabPermissions['had_kifayah'])
                @include('tetapan-asnaf.tabs.had-kifayah')
                @endif
                
                @if($tabPermissions['had_bantuan'])
                @include('tetapan-asnaf.tabs.had-bantuan')
                @endif
                
                @if($tabPermissions['workflow'])
                @include('tetapan-asnaf.tabs.workflow')
                @endif
                
                @if($tabPermissions['permohonan'])
                @include('tetapan-asnaf.tabs.permohonan')
                @endif
                
                @if($tabPermissions['kategori'])
                @include('tetapan-asnaf.tabs.kategori-data')
                @endif
                
                @if($tabPermissions['payment'])
                @include('tetapan-asnaf.tabs.payment-gateway')
                @endif
                
                @if($tabPermissions['display'])
                @include('tetapan-asnaf.tabs.display-settings')
                @endif
            </div>
        </div>
    </main>
    <x-footer />

    <style>
        .tab-content {
            display: none;
        }
        .tab-content:first-of-type {
            display: block;
        }
    </style>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active state from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            const selectedContent = document.getElementById('content-' + tabName);
            if (selectedContent) {
                selectedContent.style.display = 'block';
            }
            
            // Add active state to clicked tab button
            const selectedButton = document.getElementById('tab-' + tabName);
            if (selectedButton) {
                selectedButton.classList.add('active', 'border-blue-500', 'text-blue-600');
                selectedButton.classList.remove('border-transparent', 'text-gray-500');
            }
        }

        // Activate first visible tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            const firstButton = document.querySelector('.tab-button');
            if (firstButton) {
                firstButton.click();
            }
        });

        // Show first tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            showTab('had-kifayah');
        });
    </script>
</body>
</html>
