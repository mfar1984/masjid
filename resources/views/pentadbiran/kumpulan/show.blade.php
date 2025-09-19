<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $role->name }} - E-Masjid</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="auth()->user()" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Butiran Kumpulan Akses</h1>
                        <p class="text-xs text-gray-600">Maklumat lengkap {{ $role->name }}</p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('senarai-kumpulan.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        @unless($role->is_system_role)
                        <a href="{{ route('senarai-kumpulan.edit', $role) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                            Edit
                        </a>
                        @endunless
                    </div>
                </div>

                <!-- Role Information -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Basic Info -->
                    <div class="lg:col-span-2">
                        <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Maklumat Asas</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Nama Kumpulan</label>
                                    <div class="flex items-center">
                                        <span class="text-sm mr-2">{{ $role->type_icon }}</span>
                                        <p class="text-sm font-medium text-gray-900">{{ $role->name }}</p>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Jenis</label>
                                    @if($role->is_system_role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">lock</span>
                                            Kumpulan Sistem
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">tune</span>
                                            Kumpulan Tersuai
                                        </span>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                    {!! $role->status_badge !!}
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah Kebenaran</label>
                                    <p class="text-sm text-gray-900">{{ $role->permission_count }} kebenaran</p>
                                </div>
                            </div>

                            @if($role->description)
                            <div class="mt-4">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Penerangan</label>
                                <p class="text-sm text-gray-900">{{ $role->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div>
                        <div class="bg-blue-50 border border-blue-200 rounded-sm p-4">
                            <h3 class="text-sm font-medium text-blue-900 mb-4">Statistik Pantas</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-blue-700">Dicipta</span>
                                    <span class="text-xs font-medium text-blue-900">{{ $role->created_at->format('d/m/Y') }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-blue-700">Dikemaskini</span>
                                    <span class="text-xs font-medium text-blue-900">{{ $role->updated_at->format('d/m/Y') }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-blue-700">Kebenaran Aktif</span>
                                    <span class="text-xs font-medium text-blue-900">{{ $role->permission_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permission Matrix -->
                <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Matriks Kebenaran</h3>
                    <p class="text-xs text-gray-600 mb-4">Kebenaran yang diberikan kepada kumpulan ini</p>

                    <!-- Permission Table -->
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
                                            <span class="inline-flex items-center justify-center w-5 h-5 bg-red-100 text-red-500 rounded-full" title="Hanya Super Admin">
                                                <span class="material-icons" style="font-size: 14px !important;">block</span>
                                            </span>
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
                                        @elseif(isset($role->permissions[$moduleKey][$actionKey]) && $role->permissions[$moduleKey][$actionKey])
                                            <span class="inline-flex items-center justify-center w-5 h-5 bg-green-100 text-green-600 rounded-full">
                                                <span class="material-icons" style="font-size: 14px !important;">check</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full">
                                                <span class="material-icons" style="font-size: 14px !important;">close</span>
                                            </span>
                                        @endif
                                    </td>
                                    @endforeach

                                    @foreach($actions['workflow'] as $actionKey => $actionName)
                                    <td class="px-2 py-3 text-center border-b border-gray-200 border-l border-gray-200">
                                        @if($moduleKey === 'masjids')
                                            {{-- Senarai Masjid: Hanya Super Admin sahaja --}}
                                            <span class="inline-flex items-center justify-center w-5 h-5 bg-red-100 text-red-500 rounded-full" title="Hanya Super Admin">
                                                <span class="material-icons" style="font-size: 14px !important;">block</span>
                                            </span>
                                        @elseif($moduleKey === 'users' && in_array($actionKey, ['suspend', 'reactivate']))
                                            {{-- Senarai Pengguna: Hanya suspend dan reactivate --}}
                                            @if(isset($role->permissions[$moduleKey][$actionKey]) && $role->permissions[$moduleKey][$actionKey])
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-green-100 text-green-600 rounded-full">
                                                    <span class="material-icons" style="font-size: 14px !important;">check</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full">
                                                    <span class="material-icons" style="font-size: 14px !important;">close</span>
                                                </span>
                                            @endif
                                        @elseif($moduleKey === 'users' && in_array($actionKey, ['approve', 'reject']))
                                            {{-- Senarai Pengguna: Tidak ada approve/reject --}}
                                            <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full" title="Tidak berkenaan untuk {{ $moduleName }}">
                                                <span class="material-icons" style="font-size: 14px !important;">remove</span>
                                            </span>
                                        @elseif(in_array($moduleKey, $workflowModules))
                                            @if(isset($role->permissions[$moduleKey][$actionKey]) && $role->permissions[$moduleKey][$actionKey])
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-green-100 text-green-600 rounded-full">
                                                    <span class="material-icons" style="font-size: 14px !important;">check</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full">
                                                    <span class="material-icons" style="font-size: 14px !important;">close</span>
                                                </span>
                                            @endif
                                        @else
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

                    <!-- Legend -->
                    <div class="mt-4 flex items-center space-x-4 text-xs">
                        <div class="flex items-center">
                            <span class="inline-flex items-center justify-center w-4 h-4 bg-green-100 text-green-600 rounded-full mr-2">
                                <span class="material-icons" style="font-size: 12px !important;">check</span>
                            </span>
                            <span class="text-gray-600">Dibenarkan</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center justify-center w-4 h-4 bg-gray-100 text-gray-400 rounded-full mr-2">
                                <span class="material-icons" style="font-size: 12px !important;">close</span>
                            </span>
                            <span class="text-gray-600">Tidak Dibenarkan</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-300 mr-2">-</span>
                            <span class="text-gray-600">Tidak Berkaitan</span>
                        </div>
                    </div>
                </div>

                <!-- System Role Warning -->
                @if($role->is_system_role)
                <div class="mt-6 bg-purple-50 border border-purple-200 rounded-sm p-4">
                    <div class="flex items-start">
                        <span class="material-icons text-purple-600 mr-3 mt-0.5" style="font-size: 20px !important;">info</span>
                        <div>
                            <h4 class="text-sm font-medium text-purple-900 mb-1">Kumpulan Sistem</h4>
                            <p class="text-xs text-purple-700">Ini adalah kumpulan sistem yang tidak boleh diubah atau dipadam. Kebenaran ditetapkan secara automatik oleh sistem.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
