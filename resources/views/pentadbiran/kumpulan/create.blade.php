<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kumpulan Akses - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 mb-1">Tambah Kumpulan Akses</h1>
                        <p class="text-xs text-gray-600">Cipta kumpulan akses baru dengan izin yang sesuai</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('senarai-kumpulan.index') }}" 
                           class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('senarai-kumpulan.store') }}" class="space-y-6">
                    @csrf

                    <!-- Basic Information -->
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">Maklumat Asas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Role Name -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kumpulan <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" 
                                       placeholder="Contoh: Admin, Pengguna, Moderator"
                                       class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Skop Kumpulan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Skop Kumpulan</label>
                                @if(auth()->user()->isSuperAdmin())
                                    <select name="scope_type" id="scope_type" onchange="toggleMasjidSelection()"
                                            class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="global" {{ old('scope_type', 'global') == 'global' ? 'selected' : '' }}>🌐 Global - Boleh digunakan semua masjid</option>
                                        <option value="masjid" {{ old('scope_type') == 'masjid' ? 'selected' : '' }}>🏢 Masjid Tertentu - Untuk masjid tertentu sahaja</option>
                                    </select>
                                @else
                                    <div class="w-full h-[32px] px-3 py-2 bg-gray-100 border border-gray-300 rounded text-xs text-gray-700 flex items-center">
                                        🏢 Masjid Tertentu - {{ auth()->user()->masjid->nama ?? 'Masjid Anda' }}
                                    </div>
                                    <input type="hidden" name="scope_type" value="masjid">
                                @endif
                            </div>

                            <!-- Masjid Selection (for Super Admin only) -->
                            @if(auth()->user()->isSuperAdmin())
                            <div id="masjid_selection" style="display: none;">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pilih Masjid</label>
                                <select name="masjid_id" class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Masjid --</option>
                                    @foreach($masjids as $masjid)
                                        <option value="{{ $masjid->id }}" {{ old('masjid_id') == $masjid->id ? 'selected' : '' }}>
                                            {{ $masjid->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <div class="flex items-center h-[32px]">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label class="ml-2 text-xs text-gray-700">Aktif</label>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Penerangan</label>
                            <textarea name="description" rows="3" 
                                      placeholder="Penerangan ringkas tentang kumpulan akses ini..."
                                      class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Permission Matrix -->
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4 permission-matrix">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">Izin Akses</h3>
                        <p class="text-xs text-gray-600 mb-2">Pilih izin yang sesuai untuk kumpulan ini</p>

                        <!-- Inline CSS for emergency checkbox override -->
                        <style>
                            .permission-matrix input[type="checkbox"] {
                                appearance: none !important;
                                -webkit-appearance: none !important;
                                -moz-appearance: none !important;
                                background: white !important;
                                border: 2px solid #d1d5db !important;
                                border-radius: 2px !important;
                                width: 16px !important;
                                height: 16px !important;
                                position: relative !important;
                                cursor: pointer !important;
                                transition: all 0.2s ease !important;
                            }
                            .permission-matrix input[type="checkbox"]:checked {
                                background-color: #3b82f6 !important;
                                border-color: #3b82f6 !important;
                            }
                            .permission-matrix input[type="checkbox"]:checked::before {
                                content: '✓' !important;
                                position: absolute !important;
                                top: 50% !important;
                                left: 50% !important;
                                transform: translate(-50%, -50%) !important;
                                color: white !important;
                                font-size: 12px !important;
                                font-weight: bold !important;
                                line-height: 1 !important;
                                display: block !important;
                            }
                        </style>

                        <!-- Multi-Masjid Policy Warning -->
                        <div class="bg-blue-50 border border-blue-200 rounded-sm p-3 mb-4">
                            <div class="flex items-start">
                                <span class="material-icons text-blue-600 text-sm mr-2 mt-0.5">info</span>
                                <div>
                                    <h4 class="text-xs font-medium text-blue-800 mb-1">Policy Multi-Masjid</h4>
                                    <div class="text-xs text-blue-700 space-y-1">
                                        <p><strong>🚫 Senarai Masjid:</strong> Hanya <strong>Super Admin</strong> (global registry)</p>
                                        <p><strong>✅ Senarai Kumpulan:</strong> Admin Masjid boleh cipta untuk masjid mereka (isolated by <code class="bg-blue-100 px-1 rounded">masjid_id</code>)</p>
                                        <p><strong>✅ Senarai Pengguna:</strong> Admin Masjid boleh urus pengguna masjid mereka (isolated by <code class="bg-blue-100 px-1 rounded">masjid_id</code>)</p>
                                        <p><strong>✅ Modul Lain:</strong> Data isolated by <code class="bg-blue-100 px-1 rounded">masjid_id</code></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permission Table - UPDATED FOR KARIAH CHECKBOXES -->
                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-300 rounded-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 border-b border-gray-300">Kategori</th>
                                        @foreach($actions['basic'] as $actionKey => $actionName)
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">{{ $actionName }}</th>
                                        @endforeach
                                        @foreach($actions['workflow'] as $actionKey => $actionName)
                                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">{{ $actionName }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($modules as $moduleKey => $moduleName)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-xs font-medium text-gray-900 border-b border-gray-200">{{ $moduleName }}</td>
                                        
                                        @foreach($actions['basic'] as $actionKey => $actionName)
                                        <td class="px-2 py-3 text-center border-b border-gray-200 border-l border-gray-200">
                                            @if($moduleKey === 'fail')
                                                {{-- Fail: Header sahaja, tiada checkbox --}}
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full" title="Header sahaja">
                                                    <span class="material-icons" style="font-size: 14px !important;">remove</span>
                                                </span>
                                            @elseif($moduleKey === 'masjids')
                                                {{-- Senarai Masjid: Hanya Super Admin sahaja --}}
                                                <div class="flex flex-col items-center">
                                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-red-100 text-red-500 rounded-full" title="Hanya Super Admin">
                                                        <span class="material-icons" style="font-size: 14px !important;">block</span>
                                                    </span>
                                                    <span class="text-xs text-red-500 mt-1">Super Admin</span>
                                                </div>
                                            @elseif(in_array($moduleKey, $readOnlyModules) && $actionKey !== 'read')
                                                {{-- Read-Only Modules: Hanya ada checkbox Lihat sahaja --}}
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full" title="Tidak berkenaan untuk {{ $moduleName }}">
                                                    <span class="material-icons" style="font-size: 14px !important;">remove</span>
                                                </span>
                                            @elseif(in_array($moduleKey, $settingsOnlyModules) && !in_array($actionKey, ['read', 'update']))
                                                {{-- Settings Modules: Hanya ada checkbox Lihat dan Kemaskini sahaja --}}
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full" title="Tidak berkenaan untuk {{ $moduleName }}">
                                                    <span class="material-icons" style="font-size: 14px !important;">remove</span>
                                                </span>
                                            @else
                                                <input type="checkbox"
                                                       name="permissions[{{ $moduleKey }}][{{ $actionKey }}]"
                                                       value="1"
                                                       {{ old("permissions.{$moduleKey}.{$actionKey}") ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            @endif
                                        </td>
                                        @endforeach

                                        @foreach($actions['workflow'] as $actionKey => $actionName)
                                        <td class="px-2 py-3 text-center border-b border-gray-200 border-l border-gray-200">
                                            @if($moduleKey === 'masjids')
                                                {{-- Senarai Masjid: Hanya Super Admin sahaja --}}
                                                <div class="flex flex-col items-center">
                                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-red-100 text-red-500 rounded-full" title="Hanya Super Admin">
                                                        <span class="material-icons" style="font-size: 14px !important;">block</span>
                                                    </span>
                                                    <span class="text-xs text-red-500 mt-1">Super Admin</span>
                                                </div>
                                            @elseif($moduleKey === 'users' && in_array($actionKey, ['suspend', 'reactivate']))
                                                {{-- Senarai Pengguna: Hanya suspend dan reactivate --}}
                                                <input type="checkbox"
                                                       name="permissions[{{ $moduleKey }}][{{ $actionKey }}]"
                                                       value="1"
                                                       {{ old("permissions.{$moduleKey}.{$actionKey}") ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            @elseif($moduleKey === 'users' && in_array($actionKey, ['approve', 'reject']))
                                                {{-- Senarai Pengguna: Tidak ada approve/reject --}}
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full" title="Tidak berkenaan untuk {{ $moduleName }}">
                                                    <span class="material-icons" style="font-size: 14px !important;">remove</span>
                                                </span>
                                            @elseif(in_array($moduleKey, $workflowModules))
                                                {{-- Other modules with workflow actions --}}
                                                <input type="checkbox"
                                                       name="permissions[{{ $moduleKey }}][{{ $actionKey }}]"
                                                       value="1"
                                                       {{ old("permissions.{$moduleKey}.{$actionKey}") ? 'checked' : '' }}
                                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            @else
                                                {{-- Other modules don't have workflow actions --}}
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full" title="Tidak berkenaan untuk {{ $moduleName }}">
                                                    <span class="material-icons" style="font-size: 14px !important;">remove</span>
                                                </span>
                                            @endif
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="button" onclick="selectAll()" 
                                    class="inline-flex items-center px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                <span class="material-icons mr-1" style="font-size: 14px !important;">select_all</span>
                                Pilih Semua
                            </button>
                            <button type="button" onclick="selectNone()" 
                                    class="inline-flex items-center px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                <span class="material-icons mr-1" style="font-size: 14px !important;">deselect</span>
                                Nyahpilih Semua
                            </button>
                            <button type="button" onclick="selectReadOnly()" 
                                    class="inline-flex items-center px-3 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">
                                <span class="material-icons mr-1" style="font-size: 14px !important;">visibility</span>
                                Lihat Sahaja
                            </button>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-2 sm:space-y-0 sm:space-x-2 pt-6 border-t border-gray-200">
                        <a href="{{ route('senarai-kumpulan.index') }}" 
                           class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">cancel</span>
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan Kumpulan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function selectAll() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="permissions"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        }

        function selectNone() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="permissions"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        }

        function selectReadOnly() {
            selectNone();
            const readCheckboxes = document.querySelectorAll('input[type="checkbox"][name*="[read]"]');
            readCheckboxes.forEach(checkbox => checkbox.checked = true);
        }

        function selectSuperAdminPermissions() {
            // Auto-select all permissions for Super Admin role
            selectAll();
        }

        function toggleMasjidSelection() {
            const scopeType = document.getElementById('scope_type');
            const masjidSelection = document.getElementById('masjid_selection');

            if (scopeType && masjidSelection) {
                if (scopeType.value === 'masjid') {
                    masjidSelection.style.display = 'block';
                } else {
                    masjidSelection.style.display = 'none';
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleMasjidSelection();

            // Auto-check permissions for Super Admin role
            const nameInput = document.querySelector('input[name="name"]');

            if (nameInput) {
                nameInput.addEventListener('input', function() {
                    const roleName = this.value.toLowerCase().trim();

                    // Auto-select all permissions if creating Super Admin role
                    if (roleName === 'super admin' || roleName === 'superadmin') {
                        selectSuperAdminPermissions();
                    }
                });
            }

            // Emergency checkbox styling enforcement
            function enforceCheckboxStyling() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    // Force inline styles to override any framework
                    checkbox.style.setProperty('appearance', 'none', 'important');
                    checkbox.style.setProperty('-webkit-appearance', 'none', 'important');
                    checkbox.style.setProperty('-moz-appearance', 'none', 'important');
                    checkbox.style.setProperty('background', 'white', 'important');
                    checkbox.style.setProperty('border', '2px solid #d1d5db', 'important');
                    checkbox.style.setProperty('border-radius', '2px', 'important');
                    checkbox.style.setProperty('width', '16px', 'important');
                    checkbox.style.setProperty('height', '16px', 'important');
                    checkbox.style.setProperty('position', 'relative', 'important');
                    checkbox.style.setProperty('cursor', 'pointer', 'important');

                    // Handle checked state
                    if (checkbox.checked) {
                        checkbox.style.setProperty('background-color', '#3b82f6', 'important');
                        checkbox.style.setProperty('border-color', '#3b82f6', 'important');
                    }

                    // Add event listeners for state changes
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            this.style.setProperty('background-color', '#3b82f6', 'important');
                            this.style.setProperty('border-color', '#3b82f6', 'important');
                        } else {
                            this.style.setProperty('background-color', 'white', 'important');
                            this.style.setProperty('border-color', '#d1d5db', 'important');
                        }
                    });
                });
            }

            // Run checkbox styling enforcement
            enforceCheckboxStyling();
            setTimeout(enforceCheckboxStyling, 100);
            setTimeout(enforceCheckboxStyling, 500);
        });
    </script>
</body>
</html>
