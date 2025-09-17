<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ahli Kariah - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <script>
        function formatIC(input) {
            // Remove all non-digits
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 12 digits
            if (value.length > 12) {
                value = value.substring(0, 12);
            }
            
            // Format with dashes: DDMMYY-DD-DDDD
            if (value.length >= 6) {
                value = value.substring(0, 6) + '-' + value.substring(6);
            }
            if (value.length >= 9) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }
            
            input.value = value;
        }
    </script>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col">
    <x-double-navbar :user="$user" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Ahli Kariah</h1>
                            <p class="text-xs text-gray-600">Tambah ahli kariah baru ke dalam sistem</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('kariah.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-xs font-medium text-gray-700 mb-2">Nama Penuh *</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('nama') border-b-red-500 @enderror"
                                placeholder="Contoh: Ahmad bin Ali">
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. IC -->
                        <div>
                            <label for="no_ic" class="block text-xs font-medium text-gray-700 mb-2">Nombor IC *</label>
                            <input type="text" id="no_ic" name="no_ic" value="{{ old('no_ic') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('no_ic') border-b-red-500 @enderror"
                                placeholder="Contoh: 891230-13-1581" maxlength="14" oninput="formatIC(this)">
                            @error('no_ic')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div>
                            <label for="telefon" class="block text-xs font-medium text-gray-700 mb-2">Nombor Telefon *</label>
                            <input type="text" id="telefon" name="telefon" value="{{ old('telefon') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('telefon') border-b-red-500 @enderror"
                                placeholder="Contoh: 012-3456789">
                            @error('telefon')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bangsa -->
                        <div>
                            <label for="bangsa" class="block text-xs font-medium text-gray-700 mb-2">Bangsa *</label>
                            <select id="bangsa" name="bangsa" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('bangsa') border-b-red-500 @enderror">
                                <option value="">Pilih Bangsa</option>
                                
                                <!-- Melayu & Bumiputera Semenanjung -->
                                <optgroup label="Melayu & Bumiputera Semenanjung">
                                    <option value="Melayu" {{ old('bangsa') == 'Melayu' ? 'selected' : '' }}>Melayu</option>
                                    <option value="Orang Asli" {{ old('bangsa') == 'Orang Asli' ? 'selected' : '' }}>Orang Asli</option>
                                    <option value="Jakun" {{ old('bangsa') == 'Jakun' ? 'selected' : '' }}>Jakun</option>
                                    <option value="Temuan" {{ old('bangsa') == 'Temuan' ? 'selected' : '' }}>Temuan</option>
                                    <option value="Semai" {{ old('bangsa') == 'Semai' ? 'selected' : '' }}>Semai</option>
                                    <option value="Che Wong" {{ old('bangsa') == 'Che Wong' ? 'selected' : '' }}>Che Wong</option>
                                    <option value="Mah Meri" {{ old('bangsa') == 'Mah Meri' ? 'selected' : '' }}>Mah Meri</option>
                                    <option value="Temiar" {{ old('bangsa') == 'Temiar' ? 'selected' : '' }}>Temiar</option>
                                </optgroup>
                                
                                <!-- Cina -->
                                <optgroup label="Cina">
                                    <option value="Cina Hokkien" {{ old('bangsa') == 'Cina Hokkien' ? 'selected' : '' }}>Cina Hokkien</option>
                                    <option value="Cina Hakka" {{ old('bangsa') == 'Cina Hakka' ? 'selected' : '' }}>Cina Hakka</option>
                                    <option value="Cina Teochew" {{ old('bangsa') == 'Cina Teochew' ? 'selected' : '' }}>Cina Teochew</option>
                                    <option value="Cina Cantonese" {{ old('bangsa') == 'Cina Cantonese' ? 'selected' : '' }}>Cina Cantonese</option>
                                    <option value="Cina Hainanese" {{ old('bangsa') == 'Cina Hainanese' ? 'selected' : '' }}>Cina Hainanese</option>
                                    <option value="Cina Foochow" {{ old('bangsa') == 'Cina Foochow' ? 'selected' : '' }}>Cina Foochow</option>
                                    <option value="Cina Kwongsai" {{ old('bangsa') == 'Cina Kwongsai' ? 'selected' : '' }}>Cina Kwongsai</option>
                                    <option value="Cina Lain-lain" {{ old('bangsa') == 'Cina Lain-lain' ? 'selected' : '' }}>Cina Lain-lain</option>
                                </optgroup>
                                
                                <!-- India -->
                                <optgroup label="India">
                                    <option value="India Tamil" {{ old('bangsa') == 'India Tamil' ? 'selected' : '' }}>India Tamil</option>
                                    <option value="India Telugu" {{ old('bangsa') == 'India Telugu' ? 'selected' : '' }}>India Telugu</option>
                                    <option value="India Malayalam" {{ old('bangsa') == 'India Malayalam' ? 'selected' : '' }}>India Malayalam</option>
                                    <option value="India Punjabi" {{ old('bangsa') == 'India Punjabi' ? 'selected' : '' }}>India Punjabi</option>
                                    <option value="India Gujarati" {{ old('bangsa') == 'India Gujarati' ? 'selected' : '' }}>India Gujarati</option>
                                    <option value="India Bengali" {{ old('bangsa') == 'India Bengali' ? 'selected' : '' }}>India Bengali</option>
                                    <option value="India Lain-lain" {{ old('bangsa') == 'India Lain-lain' ? 'selected' : '' }}>India Lain-lain</option>
                                </optgroup>
                                
                                <!-- Bumiputera Sabah -->
                                <optgroup label="Bumiputera Sabah">
                                    <option value="Kadazan" {{ old('bangsa') == 'Kadazan' ? 'selected' : '' }}>Kadazan</option>
                                    <option value="Dusun" {{ old('bangsa') == 'Dusun' ? 'selected' : '' }}>Dusun</option>
                                    <option value="Bajau" {{ old('bangsa') == 'Bajau' ? 'selected' : '' }}>Bajau</option>
                                    <option value="Murut" {{ old('bangsa') == 'Murut' ? 'selected' : '' }}>Murut</option>
                                    <option value="Rungus" {{ old('bangsa') == 'Rungus' ? 'selected' : '' }}>Rungus</option>
                                    <option value="Lundayeh" {{ old('bangsa') == 'Lundayeh' ? 'selected' : '' }}>Lundayeh</option>
                                    <option value="Tidong" {{ old('bangsa') == 'Tidong' ? 'selected' : '' }}>Tidong</option>
                                    <option value="Bumiputera Sabah Lain-lain" {{ old('bangsa') == 'Bumiputera Sabah Lain-lain' ? 'selected' : '' }}>Bumiputera Sabah Lain-lain</option>
                                </optgroup>
                                
                                <!-- Bumiputera Sarawak -->
                                <optgroup label="Bumiputera Sarawak">
                                    <option value="Iban" {{ old('bangsa') == 'Iban' ? 'selected' : '' }}>Iban</option>
                                    <option value="Bidayuh" {{ old('bangsa') == 'Bidayuh' ? 'selected' : '' }}>Bidayuh</option>
                                    <option value="Melanau" {{ old('bangsa') == 'Melanau' ? 'selected' : '' }}>Melanau</option>
                                    <option value="Kayan" {{ old('bangsa') == 'Kayan' ? 'selected' : '' }}>Kayan</option>
                                    <option value="Kenyah" {{ old('bangsa') == 'Kenyah' ? 'selected' : '' }}>Kenyah</option>
                                    <option value="Kelabit" {{ old('bangsa') == 'Kelabit' ? 'selected' : '' }}>Kelabit</option>
                                    <option value="Lun Bawang" {{ old('bangsa') == 'Lun Bawang' ? 'selected' : '' }}>Lun Bawang</option>
                                    <option value="Penan" {{ old('bangsa') == 'Penan' ? 'selected' : '' }}>Penan</option>
                                    <option value="Bumiputera Sarawak Lain-lain" {{ old('bangsa') == 'Bumiputera Sarawak Lain-lain' ? 'selected' : '' }}>Bumiputera Sarawak Lain-lain</option>
                                </optgroup>
                                
                                <!-- Lain-lain -->
                                <optgroup label="Lain-lain">
                                    <option value="Eurasian" {{ old('bangsa') == 'Eurasian' ? 'selected' : '' }}>Eurasian</option>
                                    <option value="Siam" {{ old('bangsa') == 'Siam' ? 'selected' : '' }}>Siam</option>
                                    <option value="Arab" {{ old('bangsa') == 'Arab' ? 'selected' : '' }}>Arab</option>
                                    <option value="Parsi" {{ old('bangsa') == 'Parsi' ? 'selected' : '' }}>Parsi</option>
                                    <option value="Lain-lain" {{ old('bangsa') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </optgroup>
                            </select>
                            @error('bangsa')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tarikh Keahlian -->
                        <div>
                            <label for="tarikh_keahlian" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Keahlian *</label>
                            <input type="date" id="tarikh_keahlian" name="tarikh_keahlian" value="{{ old('tarikh_keahlian') }}" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('tarikh_keahlian') border-b-red-500 @enderror">
                            @error('tarikh_keahlian')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('status') border-b-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Zon -->
                        <div>
                            <label for="zon" class="block text-xs font-medium text-gray-700 mb-2">Zon</label>
                            <select id="zon" name="zon"
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('zon') border-b-red-500 @enderror">
                                <option value="">Pilih Zon</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone }}" {{ old('zon') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                                @endforeach
                                <option value="Zon Baru" {{ old('zon') == 'Zon Baru' ? 'selected' : '' }}>Zon Baru</option>
                            </select>
                            @error('zon')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('email') border-b-red-500 @enderror"
                                placeholder="Contoh: ahmad.ali@email.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-3 py-2 border-b-2 border-gray-300 text-xs focus:border-b-2 focus:border-blue-500 focus:outline-none @error('alamat') border-b-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <a href="{{ route('kariah.index') }}" class="px-4 py-2 text-xs text-gray-700 bg-gray-100 rounded-xs hover:bg-gray-200">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 text-xs text-white bg-blue-600 rounded-xs hover:bg-blue-700">
                            Simpan Ahli Kariah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
