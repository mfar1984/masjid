<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Pengguna - {{ $user->name }} - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Lihat Pengguna</h1>
                        <p class="text-xs text-gray-600">Maklumat terperinci pengguna {{ $user->name }}</p>
                    </div>
                    <div class="flex items-center justify-center md:justify-end space-x-2">
                        @if(auth()->user()->hasPermission('users', 'update'))
                            <a href="{{ route('senarai-pengguna.edit', $user) }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                                Edit
                            </a>
                        @endif
                        <a href="{{ route('senarai-pengguna.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- User Profile Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-sm p-6 mb-6">
                    <div class="flex items-center">
                        <!-- Avatar -->
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-xl font-bold text-blue-600">{{ $user->initials }}</span>
                        </div>
                        
                        <!-- User Info -->
                        <div class="flex-1">
                            <h2 class="text-lg font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-600 mb-2">{{ $user->email }}</p>
                            
                            <!-- Status & Role -->
                            <div class="flex items-center space-x-3">
                                {!! $user->status_badge !!}
                                
                                @if($user->role)
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                        @if($user->role->name === 'Super Admin') bg-purple-100 text-purple-800
                                        @elseif($user->role->name === 'Admin Masjid') bg-green-100 text-green-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        <span class="mr-1">{{ $user->role_icon }}</span>
                                        {{ $user->role->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Join Date -->
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Bergabung</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-sm mr-2">person</span>
                            Maklumat Peribadi
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-xs font-medium text-gray-500">Nama Penuh</span>
                                <span class="text-xs text-gray-900">{{ $user->name }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-xs font-medium text-gray-500">Alamat Email</span>
                                <span class="text-xs text-gray-900">{{ $user->email }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-xs font-medium text-gray-500">No. Telefon</span>
                                <span class="text-xs text-gray-900">{{ $user->phone ?? '-' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2">
                                <span class="text-xs font-medium text-gray-500">Status Email</span>
                                <span class="text-xs">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">✓ Disahkan pada {{ $user->email_verified_at->format('d/m/Y H:i') }}</span>
                                    @else
                                        <span class="text-orange-600">⚠ Belum disahkan</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Role & Access Information -->
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-sm mr-2">admin_panel_settings</span>
                            Peranan & Akses
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-xs font-medium text-gray-500">Peranan</span>
                                <span class="text-xs">
                                    @if($user->role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                            @if($user->role->name === 'Super Admin') bg-purple-100 text-purple-800
                                            @elseif($user->role->name === 'Admin Masjid') bg-green-100 text-green-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ $user->role->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">Tiada peranan</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-xs font-medium text-gray-500">Masjid</span>
                                <span class="text-xs text-gray-900">
                                    @if($user->masjid)
                                        <span class="flex items-center">
                                            <span class="mr-1">🕌</span>
                                            {{ $user->masjid->nama }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">Global Access</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-xs font-medium text-gray-500">Jenis Akses</span>
                                <span class="text-xs">
                                    @if($user->isSuperAdmin())
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">admin_panel_settings</span>
                                            Global Admin
                                        </span>
                                    @elseif($user->isAdminMasjid())
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">mosque</span>
                                            Masjid Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">person</span>
                                            Standard User
                                        </span>
                                    @endif
                                </span>
                            </div>
                            
                            @if($user->role && $user->role->description)
                            <div class="flex justify-between items-start py-2">
                                <span class="text-xs font-medium text-gray-500">Penerangan Peranan</span>
                                <span class="text-xs text-gray-900 text-right max-w-xs">{{ $user->role->description }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Activity -->
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-sm p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                        <span class="material-icons text-sm mr-2">history</span>
                        Aktiviti Akaun
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-3 bg-white rounded border">
                            <div class="text-lg font-bold text-blue-600">{{ $user->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">Tarikh Daftar</div>
                        </div>
                        
                        <div class="text-center p-3 bg-white rounded border">
                            <div class="text-lg font-bold text-green-600">{{ $user->updated_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">Kemaskini Terakhir</div>
                        </div>
                        
                        <div class="text-center p-3 bg-white rounded border">
                            <div class="text-lg font-bold text-purple-600">
                                @if($user->email_verified_at)
                                    {{ $user->email_verified_at->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">Email Disahkan</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                    @if($user->id !== auth()->id() && auth()->user()->hasPermission('users', 'delete'))
                        <button onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">delete</span>
                            Padam Pengguna
                        </button>
                    @endif

                    @if(auth()->user()->hasPermission('users', 'update'))
                        <a href="{{ route('senarai-pengguna.edit', $user) }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">edit</span>
                            Edit Pengguna
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Confirmation Modal -->
    @if(auth()->user()->hasPermission('users', 'delete'))
        <x-delete-modal
            title="Padam Pengguna"
            message="Adakah anda pasti mahu memadamkan pengguna"
        />
    @endif
</body>
</html>
