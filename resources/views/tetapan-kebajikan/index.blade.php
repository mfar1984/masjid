<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetapan Kebajikan - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tetapan Kebajikan</h1>
                    <p class="text-xs text-gray-600">Konfigurasi sistem kebajikan</p>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        @if($tabPermissions['had_bantuan'])
                        <button onclick="switchTab('had-bantuan')" id="tab-had-bantuan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-blue-600 text-blue-600">
                            Had Bantuan
                        </button>
                        @endif
                        
                        @if($tabPermissions['workflow'])
                        <button onclick="switchTab('workflow')" id="tab-workflow" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Workflow
                        </button>
                        @endif
                        
                        @if($tabPermissions['permohonan'])
                        <button onclick="switchTab('permohonan')" id="tab-permohonan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Permohonan
                        </button>
                        @endif
                        
                        @if($tabPermissions['kategori_penerima'])
                        <button onclick="switchTab('kategori')" id="tab-kategori" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Kategori Penerima
                        </button>
                        @endif
                        
                        @if($tabPermissions['pembayaran'])
                        <button onclick="switchTab('pembayaran')" id="tab-pembayaran" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Pembayaran
                        </button>
                        @endif
                        
                        @if($tabPermissions['display'])
                        <button onclick="switchTab('display')" id="tab-display" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Paparan
                        </button>
                        @endif
                        
                        @if($tabPermissions['kategori'])
                        <button onclick="switchTab('kategori-data')" id="tab-kategori-data" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Kategori
                        </button>
                        @endif
                    </nav>
                </div>

                <form method="POST" action="{{ route('tetapan-kebajikan.update') }}">
                    @csrf

                    <!-- Tab Contents -->
                    @if($tabPermissions['had_bantuan'])
                    <div id="content-had-bantuan" class="tab-content active">
                        @include('tetapan-kebajikan.tabs.had-bantuan')
                    </div>
                    @endif

                    @if($tabPermissions['workflow'])
                    <div id="content-workflow" class="tab-content">
                        @include('tetapan-kebajikan.tabs.workflow')
                    </div>
                    @endif

                    @if($tabPermissions['permohonan'])
                    <div id="content-permohonan" class="tab-content">
                        @include('tetapan-kebajikan.tabs.permohonan')
                    </div>
                    @endif

                    @if($tabPermissions['kategori_penerima'])
                    <div id="content-kategori" class="tab-content">
                        @include('tetapan-kebajikan.tabs.kategori')
                    </div>
                    @endif

                    @if($tabPermissions['pembayaran'])
                    <div id="content-pembayaran" class="tab-content">
                        @include('tetapan-kebajikan.tabs.pembayaran')
                    </div>
                    @endif

                    @if($tabPermissions['kategori'])
                    <div id="content-kategori-data" class="tab-content">
                        @include('tetapan-kebajikan.tabs.kategori-data')
                    </div>
                    @endif

                    @if($tabPermissions['display'])
                    <div id="content-display" class="tab-content">
                        @include('tetapan-kebajikan.tabs.display')
                    </div>
                    @endif

                    <!-- Save Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan Tetapan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active state from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-600', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.add('active');

            // Add active state to selected tab button
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-blue-600', 'text-blue-600');
        }

        // Activate first visible tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            const firstButton = document.querySelector('.tab-button');
            if (firstButton) {
                firstButton.click();
            }
        });
    </script>
</body>
</html>
