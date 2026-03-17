<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetapan Kewangan - E-Masjid</title>
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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tetapan Kewangan</h1>
                    <p class="text-xs text-gray-600">Konfigurasi sistem kewangan masjid</p>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        @if($tabPermissions['display'])
                        <button onclick="switchTab('umum')" id="tab-umum" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-blue-600 text-blue-600">
                            Tetapan Umum
                        </button>
                        @endif
                        
                        @if($tabPermissions['kategori'])
                        <button onclick="switchTab('kategori')" id="tab-kategori" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Kategori
                        </button>
                        @endif
                    </nav>
                </div>

                <form method="POST" action="{{ route('tetapan-kewangan.update') }}">
                    @csrf

                    <!-- Tab 1: Tetapan Umum -->
                    @if($tabPermissions['display'])
                    <div id="content-umum" class="tab-content active">
                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4">Tetapan Sistem</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="records_per_page" class="block text-xs font-medium text-gray-700 mb-2">Rekod Per Halaman</label>
                                    <input type="number" id="records_per_page" name="records_per_page" value="{{ old('records_per_page', $settings['records_per_page'] ?? 10) }}" min="5" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    @error('records_per_page')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label for="default_currency" class="block text-xs font-medium text-gray-700 mb-2">Mata Wang</label>
                                    <input type="text" id="default_currency" name="default_currency" value="{{ old('default_currency', $settings['default_currency'] ?? 'RM') }}" maxlength="10" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    @error('default_currency')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label for="fiscal_year_start" class="block text-xs font-medium text-gray-700 mb-2">Mula Tahun Kewangan</label>
                                    <select id="fiscal_year_start" name="fiscal_year_start" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('fiscal_year_start', $settings['fiscal_year_start'] ?? 1) == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('fiscal_year_start')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label for="receipt_prefix" class="block text-xs font-medium text-gray-700 mb-2">Prefix Resit</label>
                                    <input type="text" id="receipt_prefix" name="receipt_prefix" value="{{ old('receipt_prefix', $settings['receipt_prefix'] ?? 'TXN') }}" maxlength="10" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    @error('receipt_prefix')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4">Workflow & Kelulusan</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="enable_approval_workflow" value="1" {{ old('enable_approval_workflow', $settings['enable_approval_workflow'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-xs text-gray-700">Aktifkan Workflow Kelulusan</span>
                                    </label>
                                </div>

                                <div>
                                    <label for="approval_threshold" class="block text-xs font-medium text-gray-700 mb-2">Had Kelulusan (RM)</label>
                                    <input type="number" id="approval_threshold" name="approval_threshold" value="{{ old('approval_threshold', $settings['approval_threshold'] ?? 1000) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <p class="text-[10px] text-gray-500 mt-1">Perbelanjaan melebihi jumlah ini memerlukan kelulusan</p>
                                    @error('approval_threshold')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4">
                            <h2 class="text-sm font-semibold text-gray-900 mb-4">Notifikasi & Lain-lain</h2>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="auto_generate_receipt" value="1" {{ old('auto_generate_receipt', $settings['auto_generate_receipt'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-xs text-gray-700">Auto Generate No. Transaksi</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="enable_notifications" value="1" {{ old('enable_notifications', $settings['enable_notifications'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-xs text-gray-700">Aktifkan Notifikasi</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tab 2: Kategori -->
                    @if($tabPermissions['kategori'])
                    <div id="content-kategori" class="tab-content">
                        @include('tetapan-kewangan.tabs.kategori-data')
                    </div>
                    @endif

                    <!-- Tab 4: Kategori Perbelanjaan -->
                    <div id="content-kategori-perbelanjaan" class="tab-content">
                        <div class="bg-red-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-sm font-semibold text-gray-900">Senarai Kategori Perbelanjaan</h2>
                                <button type="button" onclick="openAddModal('perbelanjaan')" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                    <span class="material-icons mr-1" style="font-size: 14px !important;">add</span>
                                    Tambah Kategori
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="bg-red-100">
                                        <tr>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Nama Kategori</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Kod</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Urutan</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @forelse($kategoriPerbelanjaan as $kategori)
                                        <tr>
                                            <td class="px-4 py-2 text-xs text-gray-900">{{ $kategori->nama_kategori }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-600">{{ $kategori->kod_kategori }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-600">{{ $kategori->urutan }}</td>
                                            <td class="px-4 py-2 text-xs">
                                                @if($kategori->status == 'Aktif')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-xs">
                                                <button type="button" onclick="editKategori({{ $kategori->id }}, 'perbelanjaan')" class="text-blue-600 hover:text-blue-800 mr-2">
                                                    <span class="material-icons" style="font-size: 16px !important;">edit</span>
                                                </button>
                                                <button type="button" onclick="deleteKategori({{ $kategori->id }})" class="text-red-600 hover:text-red-800">
                                                    <span class="material-icons" style="font-size: 16px !important;">delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                <p class="text-sm">Tiada kategori perbelanjaan</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-600', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('content-' + tabName).classList.add('active');
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-blue-600', 'text-blue-600');
        }

        // Check URL parameter for tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab) {
                switchTab(tab);
            } else {
                // Activate first visible tab if no URL parameter
                const firstButton = document.querySelector('.tab-button');
                if (firstButton) {
                    firstButton.click();
                }
            }
        });
    </script>
</body>
</html>
