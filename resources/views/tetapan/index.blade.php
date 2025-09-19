<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetapan Umum - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tetapan Umum</h1>
                        @if(auth()->user()->isSuperAdmin())
                            <p class="text-xs text-gray-600">
                                Konfigurasi sistem dan tetapan asas untuk <strong>{{ $selectedMasjid->nama ?? 'masjid terpilih' }}</strong>
                                <br>
                                <span class="text-blue-600">Diurus oleh: Super Administrator ({{ auth()->user()->email }})</span>
                            </p>
                        @else
                            <p class="text-xs text-gray-600">Konfigurasi sistem dan tetapan asas untuk {{ auth()->user()->masjid->nama ?? 'masjid anda' }}</p>
                        @endif
                    </div>

                    @if(auth()->user()->isSuperAdmin() && $masjids->count() > 0)
                    <!-- Masjid Selector for Super Admin -->
                    <div class="flex items-center space-x-2">
                        <label for="masjid_selector" class="text-xs font-medium text-gray-700 whitespace-nowrap">Pilih Masjid:</label>
                            <select id="masjid_selector" 
                                    onchange="window.location.href = '{{ route('tetapan.index') }}?masjid_id=' + this.value"
                                    class="px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]">
                                <!-- Super Admin Personal Settings Option -->
                                <option value="personal" {{ $selectedMasjidId === 'personal' ? 'selected' : '' }}>
                                    🏠 Tetapan Peribadi (Super Admin)
                                </option>
                                <optgroup label="Masjid">
                                    @foreach($masjids as $masjid)
                                        <option value="{{ $masjid->id }}" {{ $selectedMasjidId == $masjid->id ? 'selected' : '' }}>
                                            {{ $masjid->nama }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                    </div>
                    @endif
                </div>

                <!-- Settings Form -->
                <form method="POST" action="{{ route('tetapan.bulk-update') }}" class="space-y-6">
                    @csrf
                    @php $canUpdate = auth()->user()->hasPermission('settings', 'update'); @endphp
                    
                    @if(auth()->user()->isSuperAdmin())
                    <!-- Hidden field for masjid_id for Super Admin -->
                    <input type="hidden" name="masjid_id" value="{{ $selectedMasjidId }}">
                    @endif
                    
                    <!-- Tetapan Umum Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-blue-600">settings</span>
                            Tetapan Umum
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Sistem -->
                            <div>
                                <label for="nama_sistem" class="block text-xs font-medium text-gray-700 mb-2">Nama Sistem</label>
                                <input type="text"
                                       id="nama_sistem"
                                       name="tetapan[nama_sistem]"
                                       value="{{ $tetapan->where('kunci', 'nama_sistem')->first()?->nilai ?? 'E-Masjid' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ !$canUpdate ? 'bg-gray-100' : '' }}"
                                       placeholder="Masukkan nama sistem"
                                       {{ !$canUpdate ? 'readonly' : '' }}>
                                <p class="mt-1 text-xs text-gray-500">Nama rasmi sistem pengurusan masjid</p>
                            </div>

                            <!-- Versi Sistem -->
                            <div>
                                <label for="versi_sistem" class="block text-xs font-medium text-gray-700 mb-2">Versi Sistem</label>
                                <input type="text" 
                                       id="versi_sistem" 
                                       name="tetapan[versi_sistem]" 
                                       value="{{ $currentVersion }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 bg-gray-100 font-mono"
                                       readonly>
                                <p class="mt-1 text-xs text-gray-500">
                                    Versi semasa: {{ $currentVersion }} - 
                                    <a href="{{ route('bantuan.nota-keluaran') }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                        Lihat sejarah keluaran
                                    </a>
                                </p>
                            </div>

                            <!-- Alamat Sistem -->
                            <div class="md:col-span-2">
                                <label for="alamat_sistem" class="block text-xs font-medium text-gray-700 mb-2">Alamat Sistem</label>
                                <input type="text"
                                       id="alamat_sistem"
                                       name="tetapan[alamat_sistem]"
                                       value="{{ $tetapan->where('kunci', 'alamat_sistem')->first()?->nilai ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ !$canUpdate ? 'bg-gray-100' : '' }}"
                                       placeholder="Masukkan alamat sistem"
                                       {{ !$canUpdate ? 'readonly' : '' }}>
                                <p class="mt-1 text-xs text-gray-500">Alamat rasmi sistem</p>
                            </div>

                            <!-- Lokasi Default Latitude -->
                            <div>
                                <label for="default_latitude" class="block text-xs font-medium text-gray-700 mb-2">Latitude Default</label>
                                <input type="number" 
                                       id="default_latitude" 
                                       name="tetapan[default_latitude]" 
                                       value="{{ $tetapan->where('kunci', 'default_latitude')->first()?->nilai ?? '2.3000' }}"
                                       step="any"
                                       min="-90" max="90"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Contoh: 2.3000">
                                <p class="mt-1 text-xs text-gray-500">Latitude default untuk maps (Kuching: 2.3000)</p>
                            </div>

                            <!-- Lokasi Default Longitude -->
                            <div>
                                <label for="default_longitude" class="block text-xs font-medium text-gray-700 mb-2">Longitude Default</label>
                                <input type="number" 
                                       id="default_longitude" 
                                       name="tetapan[default_longitude]" 
                                       value="{{ $tetapan->where('kunci', 'default_longitude')->first()?->nilai ?? '111.8167' }}"
                                       step="any"
                                       min="-180" max="180"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Contoh: 111.8167">
                                <p class="mt-1 text-xs text-gray-500">Longitude default untuk maps (Kuching: 111.8167)</p>
                            </div>

                            <!-- Zon Waktu Solat (e-Solat) -->
                            <div>
                                <label for="prayer_zone" class="block text-xs font-medium text-gray-700 mb-2">Zon Waktu Solat (e‑Solat JAKIM)</label>
                                @php $selectedZone = $tetapan->where('kunci', 'prayer_zone')->first()?->nilai ?? 'SWK08'; @endphp
                                <select id="prayer_zone" name="tetapan[prayer_zone]" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="" disabled {{ $selectedZone ? '' : 'selected' }}>Pilih Zon...</option>
                                    <optgroup label="Johor">
                                        <option value="JHR01" {{ $selectedZone==='JHR01' ? 'selected' : '' }}>JHR01 - Pulau Aur dan Pulau Pemanggil</option>
                                        <option value="JHR02" {{ $selectedZone==='JHR02' ? 'selected' : '' }}>JHR02 - Johor Bahru, Kota Tinggi, Mersing, Kulai</option>
                                        <option value="JHR03" {{ $selectedZone==='JHR03' ? 'selected' : '' }}>JHR03 - Kluang, Pontian</option>
                                        <option value="JHR04" {{ $selectedZone==='JHR04' ? 'selected' : '' }}>JHR04 - Batu Pahat, Muar, Segamat, Gemas Johor, Tangkak</option>
                                    </optgroup>
                                    <optgroup label="Kedah">
                                        <option value="KDH01" {{ $selectedZone==='KDH01' ? 'selected' : '' }}>KDH01 - Kota Setar, Kubang Pasu, Pokok Sena (Daerah Kecil)</option>
                                        <option value="KDH02" {{ $selectedZone==='KDH02' ? 'selected' : '' }}>KDH02 - Kuala Muda, Yan, Pendang</option>
                                        <option value="KDH03" {{ $selectedZone==='KDH03' ? 'selected' : '' }}>KDH03 - Padang Terap, Sik</option>
                                        <option value="KDH04" {{ $selectedZone==='KDH04' ? 'selected' : '' }}>KDH04 - Baling</option>
                                        <option value="KDH05" {{ $selectedZone==='KDH05' ? 'selected' : '' }}>KDH05 - Bandar Baharu, Kulim</option>
                                        <option value="KDH06" {{ $selectedZone==='KDH06' ? 'selected' : '' }}>KDH06 - Langkawi</option>
                                        <option value="KDH07" {{ $selectedZone==='KDH07' ? 'selected' : '' }}>KDH07 - Puncak Gunung Jerai</option>
                                    </optgroup>
                                    <optgroup label="Kelantan">
                                        <option value="KTN01" {{ $selectedZone==='KTN01' ? 'selected' : '' }}>KTN01 - Bachok, Kota Bharu, Machang, Pasir Mas, Pasir Puteh, Tanah Merah, Tumpat, Kuala Krai, Mukim Chiku</option>
                                        <option value="KTN02" {{ $selectedZone==='KTN02' ? 'selected' : '' }}>KTN02 - Gua Musang (Daerah Galas Dan Bertam), Jeli, Jajahan Kecil Lojing</option>
                                    </optgroup>
                                    <optgroup label="Melaka">
                                        <option value="MLK01" {{ $selectedZone==='MLK01' ? 'selected' : '' }}>MLK01 - SELURUH NEGERI MELAKA</option>
                                    </optgroup>
                                    <optgroup label="Negeri Sembilan">
                                        <option value="NGS01" {{ $selectedZone==='NGS01' ? 'selected' : '' }}>NGS01 - Tampin, Jempol</option>
                                        <option value="NGS02" {{ $selectedZone==='NGS02' ? 'selected' : '' }}>NGS02 - Jelebu, Kuala Pilah, Rembau</option>
                                        <option value="NGS03" {{ $selectedZone==='NGS03' ? 'selected' : '' }}>NGS03 - Port Dickson, Seremban</option>
                                    </optgroup>
                                    <optgroup label="Pahang">
                                        <option value="PHG01" {{ $selectedZone==='PHG01' ? 'selected' : '' }}>PHG01 - Pulau Tioman</option>
                                        <option value="PHG02" {{ $selectedZone==='PHG02' ? 'selected' : '' }}>PHG02 - Kuantan, Pekan, Muadzam Shah</option>
                                        <option value="PHG03" {{ $selectedZone==='PHG03' ? 'selected' : '' }}>PHG03 - Jerantut, Temerloh, Maran, Bera, Chenor, Jengka</option>
                                        <option value="PHG04" {{ $selectedZone==='PHG04' ? 'selected' : '' }}>PHG04 - Bentong, Lipis, Raub</option>
                                        <option value="PHG05" {{ $selectedZone==='PHG05' ? 'selected' : '' }}>PHG05 - Genting Sempah, Janda Baik, Bukit Tinggi</option>
                                        <option value="PHG06" {{ $selectedZone==='PHG06' ? 'selected' : '' }}>PHG06 - Cameron Highlands, Genting Higlands, Bukit Fraser</option>
                                        <option value="PHG07" {{ $selectedZone==='PHG07' ? 'selected' : '' }}>PHG07 - Zon Khas Daerah Rompin, (Mukim Rompin, Mukim Endau, Mukim Pontian)</option>
                                    </optgroup>
                                    <optgroup label="Perlis">
                                        <option value="PLS01" {{ $selectedZone==='PLS01' ? 'selected' : '' }}>PLS01 - Kangar, Padang Besar, Arau</option>
                                    </optgroup>
                                    <optgroup label="Pulau Pinang">
                                        <option value="PNG01" {{ $selectedZone==='PNG01' ? 'selected' : '' }}>PNG01 - Seluruh Negeri Pulau Pinang</option>
                                    </optgroup>
                                    <optgroup label="Perak">
                                        <option value="PRK01" {{ $selectedZone==='PRK01' ? 'selected' : '' }}>PRK01 - Tapah, Slim River, Tanjung Malim</option>
                                        <option value="PRK02" {{ $selectedZone==='PRK02' ? 'selected' : '' }}>PRK02 - Kuala Kangsar, Sg. Siput , Ipoh, Batu Gajah, Kampar</option>
                                        <option value="PRK03" {{ $selectedZone==='PRK03' ? 'selected' : '' }}>PRK03 - Lenggong, Pengkalan Hulu, Grik</option>
                                        <option value="PRK04" {{ $selectedZone==='PRK04' ? 'selected' : '' }}>PRK04 - Temengor, Belum</option>
                                        <option value="PRK05" {{ $selectedZone==='PRK05' ? 'selected' : '' }}>PRK05 - Kg Gajah, Teluk Intan, Bagan Datuk, Seri Iskandar, Beruas, Parit, Lumut, Sitiawan, Pulau Pangkor</option>
                                        <option value="PRK06" {{ $selectedZone==='PRK06' ? 'selected' : '' }}>PRK06 - Selama, Taiping, Bagan Serai, Parit Buntar</option>
                                        <option value="PRK07" {{ $selectedZone==='PRK07' ? 'selected' : '' }}>PRK07 - Bukit Larut</option>
                                    </optgroup>
                                    <optgroup label="Sabah">
                                        <option value="SBH01" {{ $selectedZone==='SBH01' ? 'selected' : '' }}>SBH01 - Bahagian Sandakan (Timur), Bukit Garam, Semawang, Temanggong, Tambisan, Bandar Sandakan, Sukau</option>
                                        <option value="SBH02" {{ $selectedZone==='SBH02' ? 'selected' : '' }}>SBH02 - Beluran, Telupid, Pinangah, Terusan, Kuamut, Bahagian Sandakan (Barat)</option>
                                        <option value="SBH03" {{ $selectedZone==='SBH03' ? 'selected' : '' }}>SBH03 - Lahad Datu, Silabukan, Kunak, Sahabat, Semporna, Tungku, Bahagian Tawau  (Timur)</option>
                                        <option value="SBH04" {{ $selectedZone==='SBH04' ? 'selected' : '' }}>SBH04 - Bandar Tawau, Balong, Merotai, Kalabakan, Bahagian Tawau (Barat)</option>
                                        <option value="SBH05" {{ $selectedZone==='SBH05' ? 'selected' : '' }}>SBH05 - Kudat, Kota Marudu, Pitas, Pulau Banggi, Bahagian Kudat</option>
                                        <option value="SBH06" {{ $selectedZone==='SBH06' ? 'selected' : '' }}>SBH06 - Gunung Kinabalu</option>
                                        <option value="SBH07" {{ $selectedZone==='SBH07' ? 'selected' : '' }}>SBH07 - Kota Kinabalu, Ranau, Kota Belud, Tuaran, Penampang, Papar, Putatan, Bahagian Pantai Barat</option>
                                        <option value="SBH08" {{ $selectedZone==='SBH08' ? 'selected' : '' }}>SBH08 - Pensiangan, Keningau, Tambunan, Nabawan, Bahagian Pendalaman (Atas)</option>
                                        <option value="SBH09" {{ $selectedZone==='SBH09' ? 'selected' : '' }}>SBH09 - Beaufort, Kuala Penyu, Sipitang, Tenom, Long Pa Sia, Membakut, Weston, Bahagian Pendalaman (Bawah)</option>
                                    </optgroup>
                                    <optgroup label="Selangor">
                                        <option value="SGR01" {{ $selectedZone==='SGR01' ? 'selected' : '' }}>SGR01 - Gombak, Petaling, Sepang, Hulu Langat, Hulu Selangor, S.Alam</option>
                                        <option value="SGR02" {{ $selectedZone==='SGR02' ? 'selected' : '' }}>SGR02 - Kuala Selangor, Sabak Bernam</option>
                                        <option value="SGR03" {{ $selectedZone==='SGR03' ? 'selected' : '' }}>SGR03 - Klang, Kuala Langat</option>
                                    </optgroup>
                                    <optgroup label="Sarawak">
                                        <option value="SWK01" {{ $selectedZone==='SWK01' ? 'selected' : '' }}>SWK01 - Limbang, Lawas, Sundar, Trusan</option>
                                        <option value="SWK02" {{ $selectedZone==='SWK02' ? 'selected' : '' }}>SWK02 - Miri, Niah, Bekenu, Sibuti, Marudi</option>
                                        <option value="SWK03" {{ $selectedZone==='SWK03' ? 'selected' : '' }}>SWK03 - Pandan, Belaga, Suai, Tatau, Sebauh, Bintulu</option>
                                        <option value="SWK04" {{ $selectedZone==='SWK04' ? 'selected' : '' }}>SWK04 - Sibu, Mukah, Dalat, Song, Igan, Oya, Balingian, Kanowit, Kapit</option>
                                        <option value="SWK05" {{ $selectedZone==='SWK05' ? 'selected' : '' }}>SWK05 - Sarikei, Matu, Julau, Rajang, Daro, Bintangor, Belawai</option>
                                        <option value="SWK06" {{ $selectedZone==='SWK06' ? 'selected' : '' }}>SWK06 - Lubok Antu, Sri Aman, Roban, Debak, Kabong, Lingga, Engkelili, Betong, Spaoh, Pusa, Saratok</option>
                                        <option value="SWK07" {{ $selectedZone==='SWK07' ? 'selected' : '' }}>SWK07 - Serian, Simunjan, Samarahan, Sebuyau, Meludam</option>
                                        <option value="SWK08" {{ $selectedZone==='SWK08' ? 'selected' : '' }}>SWK08 - Kuching, Bau, Lundu, Sematan</option>
                                        <option value="SWK09" {{ $selectedZone==='SWK09' ? 'selected' : '' }}>SWK09 - Zon Khas (Kampung Patarikan)</option>
                                    </optgroup>
                                    <optgroup label="Terengganu">
                                        <option value="TRG01" {{ $selectedZone==='TRG01' ? 'selected' : '' }}>TRG01 - Kuala Terengganu, Marang, Kuala Nerus</option>
                                        <option value="TRG02" {{ $selectedZone==='TRG02' ? 'selected' : '' }}>TRG02 - Besut, Setiu</option>
                                        <option value="TRG03" {{ $selectedZone==='TRG03' ? 'selected' : '' }}>TRG03 - Hulu Terengganu</option>
                                        <option value="TRG04" {{ $selectedZone==='TRG04' ? 'selected' : '' }}>TRG04 - Dungun, Kemaman</option>
                                    </optgroup>
                                    <optgroup label="Wilayah Persekutuan">
                                        <option value="WLY01" {{ $selectedZone==='WLY01' ? 'selected' : '' }}>WLY01 - Kuala Lumpur, Putrajaya</option>
                                        <option value="WLY02" {{ $selectedZone==='WLY02' ? 'selected' : '' }}>WLY02 - Labuan</option>
                                    </optgroup>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Pilih zon waktu solat untuk paparan widget e-Solat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tetapan Azan Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-purple-600">volume_up</span>
                            Tetapan Azan
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Auto-Play Azan -->
                            <div>
                                <label for="azan_enabled" class="block text-xs font-medium text-gray-700 mb-2">Auto-Play Azan</label>
                                <select id="azan_enabled" 
                                        name="tetapan[azan_enabled]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ !$canUpdate ? 'bg-gray-100' : '' }}"
                                        {{ !$canUpdate ? 'disabled' : '' }}>
                                    @php $azanEnabled = $tetapan->where('kunci', 'azan_enabled')->first()?->nilai ?? '1'; @endphp
                                    <option value="1" {{ $azanEnabled === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ $azanEnabled === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Automatik mainkan azan ketika masuk waktu solat</p>
                            </div>

                            <!-- Azan Subuh/Fajr -->
                            <div>
                                <label for="azan_fajr_type" class="block text-xs font-medium text-gray-700 mb-2">Azan Subuh/Fajr</label>
                                <select id="azan_fajr_type" 
                                        name="tetapan[azan_fajr_type]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ !$canUpdate ? 'bg-gray-100' : '' }}"
                                        {{ !$canUpdate ? 'disabled' : '' }}>
                                    @php $azanFajrType = $tetapan->where('kunci', 'azan_fajr_type')->first()?->nilai ?? 'madinah-fajr'; @endphp
                                    
                                    @if($fajrAzanFiles->count() > 0)
                                        <!-- Fajr-specific files detected -->
                                        @foreach($fajrAzanFiles as $file)
                                            <option value="{{ $file['value'] }}" {{ $azanFajrType === $file['value'] ? 'selected' : '' }}>
                                                {{ $file['name'] }}
                                                <small>({{ round($file['file_size']/1024) }}KB)</small>
                                            </option>
                                        @endforeach
                                        <!-- Add regular azan as fallback options -->
                                        <optgroup label="Azan Biasa (Fallback)">
                                            @foreach($regularAzanFiles as $file)
                                                <option value="{{ $file['value'] }}" {{ $azanFajrType === $file['value'] ? 'selected' : '' }}>
                                                    {{ $file['name'] }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <!-- No fajr files detected, show all files -->
                                        @foreach($azanFiles as $file)
                                            <option value="{{ $file['value'] }}" {{ $azanFajrType === $file['value'] ? 'selected' : '' }}>
                                                {{ $file['name'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    Auto-detected {{ $fajrAzanFiles->count() }} Fajr + {{ $regularAzanFiles->count() }} regular azan files
                                </p>
                            </div>

                            <!-- Azan Waktu Biasa -->
                            <div>
                                <label for="azan_regular_type" class="block text-xs font-medium text-gray-700 mb-2">Azan Waktu Biasa</label>
                                <select id="azan_regular_type" 
                                        name="tetapan[azan_regular_type]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ !$canUpdate ? 'bg-gray-100' : '' }}"
                                        {{ !$canUpdate ? 'disabled' : '' }}>
                                    @php $azanRegularType = $tetapan->where('kunci', 'azan_regular_type')->first()?->nilai ?? 'makkah'; @endphp
                                    
                                    @if($regularAzanFiles->count() > 0)
                                        <!-- Regular azan files detected -->
                                        @foreach($regularAzanFiles as $file)
                                            <option value="{{ $file['value'] }}" {{ $azanRegularType === $file['value'] ? 'selected' : '' }}>
                                                {{ $file['name'] }}
                                                <small>({{ round($file['file_size']/1024) }}KB)</small>
                                            </option>
                                        @endforeach
                                        @if($fajrAzanFiles->count() > 0)
                                            <!-- Add Fajr files as fallback (though not recommended) -->
                                            <optgroup label="Fajr Files (Tidak Disyorkan)">
                                                @foreach($fajrAzanFiles as $file)
                                                    <option value="{{ $file['value'] }}" {{ $azanRegularType === $file['value'] ? 'selected' : '' }}>
                                                        {{ $file['name'] }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @else
                                        <!-- No regular files, show all available -->
                                        @foreach($azanFiles as $file)
                                            <option value="{{ $file['value'] }}" {{ $azanRegularType === $file['value'] ? 'selected' : '' }}>
                                                {{ $file['name'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    Untuk Zuhr, Asr, Maghrib, Isha (Imsak & Syuruk tiada azan)
                                </p>
                            </div>

                            <!-- Volume Azan -->
                            <div>
                                <label for="azan_volume" class="block text-xs font-medium text-gray-700 mb-2">Volume Azan</label>
                                <div class="flex items-center space-x-3">
                                    <span class="text-xs text-gray-500">🔉</span>
                                    <input type="range" 
                                           id="azan_volume" 
                                           name="tetapan[azan_volume]" 
                                           min="0.1" 
                                           max="1.0" 
                                           step="0.1" 
                                           value="{{ $tetapan->where('kunci', 'azan_volume')->first()?->nilai ?? '0.7' }}"
                                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer {{ !$canUpdate ? 'opacity-50' : '' }}"
                                           {{ !$canUpdate ? 'disabled' : '' }}
                                           oninput="document.getElementById('volume-display').textContent = (parseFloat(this.value) * 100).toFixed(0) + '%'">
                                    <span class="text-xs text-gray-500">🔊</span>
                                    <span id="volume-display" class="text-xs font-medium text-gray-700 min-w-[40px]">
                                        {{ (($tetapan->where('kunci', 'azan_volume')->first()?->nilai ?? 0.7) * 100) }}%
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Sesuaikan kekuatan bunyi azan (10% - 100%)</p>
                            </div>

                            <!-- Test Azan Buttons -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Test Azan</label>
                                <div class="space-y-2">
                                    <button type="button" 
                                            id="test-fajr-btn"
                                            class="w-full px-3 py-2 bg-orange-600 text-white text-xs rounded-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center">
                                        <span class="material-icons text-sm mr-2">wb_sunny</span>
                                        <span class="font-medium">Test Azan Subuh/Fajr</span>
                                    </button>
                                    <button type="button" 
                                            id="test-regular-btn"
                                            class="w-full px-3 py-2 bg-purple-600 text-white text-xs rounded-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center">
                                        <span class="material-icons text-sm mr-2">access_time</span>
                                        <span class="font-medium">Test Azan Waktu Biasa</span>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Cuba dengar kedua-dua jenis azan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tetapan Sistem Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-green-600">computer</span>
                            Tetapan Sistem
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Maksimum Percubaan Login -->
                            <div>
                                <label for="max_login_attempts" class="block text-xs font-medium text-gray-700 mb-2">Maksimum Percubaan Login</label>
                                <input type="number" 
                                       id="max_login_attempts" 
                                       name="tetapan[max_login_attempts]" 
                                       value="{{ $tetapan->where('kunci', 'max_login_attempts')->first()?->nilai ?? '5' }}"
                                       min="1" max="10"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="5">
                                <p class="mt-1 text-xs text-gray-500">Bilangan maksimum percubaan login sebelum akaun dikunci</p>
                            </div>

                            <!-- Masa Tamat Sesi (minit) -->
                            <div>
                                <label for="session_timeout" class="block text-xs font-medium text-gray-700 mb-2">Masa Tamat Sesi (minit)</label>
                                <input type="number" 
                                       id="session_timeout" 
                                       name="tetapan[session_timeout]" 
                                       value="{{ $tetapan->where('kunci', 'session_timeout')->first()?->nilai ?? '60' }}"
                                       min="15" max="480"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="60">
                                <p class="mt-1 text-xs text-gray-500">Masa dalam minit sebelum sesi tamat secara automatik</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tetapan reCAPTCHA Section -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons text-sm mr-2 text-purple-600">security</span>
                            Tetapan reCAPTCHA
                        </h2>

                        <!-- reCAPTCHA Enabled -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="tetapan[recaptcha_enabled]"
                                       value="1"
                                       {{ $tetapan->where('kunci', 'recaptcha_enabled')->first()?->nilai ? 'checked' : '' }}
                                       class="mr-2"
                                       {{ !$canUpdate ? 'disabled' : '' }}>
                                <span class="text-xs font-medium text-gray-700">Aktifkan reCAPTCHA untuk Feedback Form</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Aktifkan atau nyahaktifkan reCAPTCHA untuk melindungi feedback form dari spam</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- reCAPTCHA Site Key -->
                            <div>
                                <label for="recaptcha_site_key" class="block text-xs font-medium text-gray-700 mb-2">reCAPTCHA Site Key</label>
                                <input type="text"
                                       id="recaptcha_site_key"
                                       name="tetapan[recaptcha_site_key]"
                                       value="{{ $tetapan->where('kunci', 'recaptcha_site_key')->first()?->nilai ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="6Lc...">
                                <p class="mt-1 text-xs text-gray-500">Public key dari Google reCAPTCHA Console</p>
                            </div>

                            <!-- reCAPTCHA Secret Key -->
                            <div>
                                <label for="recaptcha_secret_key" class="block text-xs font-medium text-gray-700 mb-2">reCAPTCHA Secret Key</label>
                                <input type="password"
                                       id="recaptcha_secret_key"
                                       name="tetapan[recaptcha_secret_key]"
                                       value="{{ $tetapan->where('kunci', 'recaptcha_secret_key')->first()?->nilai ?? '' }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="6Lc...">
                                <p class="mt-1 text-xs text-gray-500">Private key dari Google reCAPTCHA Console</p>
                            </div>
                        </div>

                        <!-- reCAPTCHA Setup Instructions -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-sm border border-blue-200">
                            <h3 class="text-xs font-semibold text-blue-800 mb-2 flex items-center">
                                <span class="material-icons text-sm mr-1">info</span>
                                Cara Setup reCAPTCHA:
                            </h3>
                            <ol class="text-xs text-blue-700 space-y-1 ml-4">
                                <li>1. Pergi ke <a href="https://www.google.com/recaptcha/admin" target="_blank" class="underline">Google reCAPTCHA Console</a></li>
                                <li>2. Pilih "reCAPTCHA v2" → "Invisible reCAPTCHA badge"</li>
                                <li>3. Tambah domain: <code class="bg-blue-100 px-1 rounded">localhost:8000</code>, <code class="bg-blue-100 px-1 rounded">yourdomain.com</code></li>
                                <li>4. Copy Site Key dan Secret Key ke atas</li>
                                <li>5. Aktifkan reCAPTCHA dan simpan tetapan</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Save Button -->
                    @if($canUpdate)
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan Tetapan
                        </button>
                    </div>
                    @else
                    <div class="flex justify-end">
                        <div class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-300 text-gray-500 text-xs rounded cursor-not-allowed">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">lock</span>
                            Tiada Kebenaran Kemaskini
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Azan Test Functionality -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Test Fajr Azan Button
        const testFajrBtn = document.getElementById('test-fajr-btn');
        if (testFajrBtn) {
            testFajrBtn.addEventListener('click', async function() {
                await testAzan(this, 'fajr');
            });
        }

        // Test Regular Azan Button
        const testRegularBtn = document.getElementById('test-regular-btn');
        if (testRegularBtn) {
            testRegularBtn.addEventListener('click', async function() {
                await testAzan(this, 'regular');
            });
        }

        async function testAzan(button, type) {
            const originalText = button.innerHTML;
            
            try {
                // Disable button during test
                button.disabled = true;
                button.innerHTML = '<div class="flex items-center justify-center"><span class="material-icons text-sm mr-2 animate-spin">refresh</span><span class="font-medium">Testing...</span></div>';
                
                // Get current form values
                const azanEnabled = document.getElementById('azan_enabled').value === '1';
                const azanVolume = parseFloat(document.getElementById('azan_volume').value);
                
                if (!azanEnabled) {
                    alert('Azan tidak aktif. Sila aktifkan dahulu untuk test.');
                    return;
                }
                
                // Get azan type based on test type
                let azanType;
                let testName;
                if (type === 'fajr') {
                    azanType = document.getElementById('azan_fajr_type').value;
                    testName = 'Subuh/Fajr';
                } else {
                    azanType = document.getElementById('azan_regular_type').value;
                    testName = 'Waktu Biasa';
                }
                
                // Play test azan
                const audio = new Audio(`/audio/azan/${azanType}.mp3`);
                audio.volume = azanVolume;
                
                // Show notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-blue-600 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3';
                notification.innerHTML = `
                    <span class="material-icons text-sm">volume_up</span>
                    <div>
                        <p class="font-medium text-sm">Test Azan ${testName}</p>
                        <p class="text-xs opacity-90">Memainkan azan ${azanType} dengan volume ${Math.round(azanVolume * 100)}%</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <span class="material-icons text-sm">close</span>
                    </button>
                `;
                
                document.body.appendChild(notification);
                
                await audio.play();

                // Remove notification after audio ends or 30 seconds
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 30000);
                
            } catch (error) {
                console.error('Error playing test azan:', error);
                alert('Gagal memainkan azan. Pastikan file audio tersedia.');
            } finally {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = originalText;
            }
        }
    });
    </script>
</body>
</html>
