<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ahli Kariah - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

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
                <form method="POST" action="{{ route('kariah.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Nama Penuh *</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                                placeholder="Contoh: Ahmad bin Ali">
                            @error('nama')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. IC -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Nombor IC *</label>
                            <input type="text" name="no_ic" value="{{ old('no_ic') }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_ic') border-red-500 @enderror"
                                placeholder="Contoh: 891230-13-1581" maxlength="14" oninput="formatIC(this)">
                            @error('no_ic')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Nombor Telefon *</label>
                            <input type="text" name="telefon" value="{{ old('telefon') }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefon') border-red-500 @enderror"
                                placeholder="Contoh: 012-3456789">
                            @error('telefon')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jantina -->
                        <x-forms.input-field
                            label="Jantina"
                            name="jantina"
                            type="select"
                            required="true"
                            :error="$errors->first('jantina')"
                        >
                            <option value="">Pilih Jantina</option>
                            <option value="Lelaki" {{ old('jantina') == 'Lelaki' ? 'selected' : '' }}>Lelaki</option>
                            <option value="Perempuan" {{ old('jantina') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            <option value="Tidak Dinyatakan" {{ old('jantina') == 'Tidak Dinyatakan' ? 'selected' : '' }}>Tidak Dinyatakan</option>
                        </x-forms.input-field>

                        <!-- Bangsa -->
                        <x-forms.input-field
                            label="Bangsa"
                            name="bangsa"
                            type="select"
                            required="true"
                            :error="$errors->first('bangsa')"
                        >
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
                        </x-forms.input-field>

                        <!-- Tarikh Keahlian -->
                        <x-forms.input-field
                            label="Tarikh Keahlian"
                            name="tarikh_keahlian"
                            type="date"
                            required="true"
                            :error="$errors->first('tarikh_keahlian')"
                        />

                        <!-- Status -->
                        <x-forms.input-field
                            label="Status"
                            name="status"
                            type="select"
                            required="true"
                            :error="$errors->first('status')"
                        >
                            <option value="">Pilih Status</option>
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="Menunggu" {{ old('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        </x-forms.input-field>



                        <!-- Email -->
                        <div>
                            <label class="form-label text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                placeholder="Contoh: ahmad.ali@email.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="form-label text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Attachment Section (Moved to bottom) -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">Lampiran Dokumen</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- IC Depan -->
                            <div>
                                <label class="form-label text-gray-700 mb-1">Kad Pengenalan / Passport (Depan)</label>
                                <input type="file" name="ic_depan" accept="image/*,.pdf"
                                    class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ic_depan') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                                @error('ic_depan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- IC Belakang -->
                            <div>
                                <label class="form-label text-gray-700 mb-1">Kad Pengenalan / Passport (Belakang)</label>
                                <input type="file" name="ic_belakang" accept="image/*,.pdf"
                                    class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ic_belakang') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                                @error('ic_belakang')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <a href="{{ route('kariah.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
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
