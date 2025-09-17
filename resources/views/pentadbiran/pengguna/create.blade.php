<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Pengguna</h1>
                        <p class="text-xs text-gray-600">Cipta akaun pengguna baru dalam sistem</p>
                    </div>
                    <div class="flex items-center justify-center md:justify-end">
                        <a href="{{ route('senarai-pengguna.index') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Debug Info -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-sm p-4 mb-6">
                        <h3 class="text-sm font-medium text-red-800 mb-2">Ralat Validasi:</h3>
                        <ul class="text-xs text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-sm p-4 mb-6">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('senarai-pengguna.store') }}" class="space-y-6">
                    @csrf

                    <!-- Personal Information -->
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-sm mr-2">person</span>
                            Maklumat Peribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nama Penuh <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">No. Telefon <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                       placeholder="Contoh: 013-1234567"
                                       class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-sm mr-2">security</span>
                            Maklumat Akaun
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Kata Laluan <span class="text-red-500">*</span></label>
                                <input type="password" name="password" id="password" required
                                       class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimum 8 aksara</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">Sahkan Kata Laluan <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Role & Access -->
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                            <span class="material-icons text-sm mr-2">admin_panel_settings</span>
                            Peranan & Akses
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Role -->
                            <div>
                                <label for="role_id" class="block text-xs font-medium text-gray-700 mb-1">Peranan <span class="text-red-500">*</span></label>
                                <select name="role_id" id="role_id" required
                                        class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role_id') border-red-500 @enderror">
                                    <option value="">Pilih Peranan</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if($role->description)
                                                - {{ $role->description }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Masjid -->
                            <div>
                                <label for="masjid_id" class="block text-xs font-medium text-gray-700 mb-1">Masjid</label>
                                <select name="masjid_id" id="masjid_id"
                                        class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('masjid_id') border-red-500 @enderror">
                                    @if(auth()->user()->isSuperAdmin())
                                        <option value="">Tiada Masjid (Global Access)</option>
                                    @endif
                                    @foreach($masjids as $masjid)
                                        <option value="{{ $masjid->id }}"
                                            {{ old('masjid_id', auth()->user()->masjid_id) == $masjid->id ? 'selected' : '' }}>
                                            {{ $masjid->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('masjid_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk akses global (Super Admin sahaja)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Multi-Masjid Policy Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-sm p-3">
                        <div class="flex items-start">
                            <span class="material-icons text-blue-600 text-sm mr-2 mt-0.5">info</span>
                            <div>
                                <h4 class="text-xs font-medium text-blue-800 mb-1">Policy Multi-Masjid</h4>
                                <div class="text-xs text-blue-700 space-y-1">
                                    <p><strong>Super Admin:</strong> Akses global ke semua masjid (masjid_id = null)</p>
                                    <p><strong>Admin Masjid:</strong> Akses terhad kepada masjid yang ditetapkan sahaja</p>
                                    <p><strong>Peranan Lain:</strong> Mengikut scope masjid yang ditetapkan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('senarai-pengguna.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">cancel</span>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
