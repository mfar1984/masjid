<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tempahan Fasiliti - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Make calendar icon visible */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: invert(0.5);
            opacity: 1;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover,
        input[type="datetime-local"]::-webkit-calendar-picker-indicator:hover {
            filter: invert(0.3);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Tempahan Fasiliti</h1>
                        <p class="text-xs text-gray-600">{{ $tempahanFasiliti->no_tempahan }}</p>
                    </div>
                    <a href="{{ route('tempahan-fasiliti.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form action="{{ route('tempahan-fasiliti.update', $tempahanFasiliti->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Penyewa -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Penyewa</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Penyewa <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_penyewa" value="{{ old('nama_penyewa', $tempahanFasiliti->nama_penyewa) }}" required maxlength="255" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('nama_penyewa') border-red-500 @enderror">
                                @error('nama_penyewa')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. IC <span class="text-red-500">*</span></label>
                                <input type="text" name="no_ic_penyewa" value="{{ old('no_ic_penyewa', $tempahanFasiliti->no_ic_penyewa) }}" required maxlength="12" placeholder="000000000000" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('no_ic_penyewa') border-red-500 @enderror">
                                @error('no_ic_penyewa')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Telefon <span class="text-red-500">*</span></label>
                                <input type="text" name="no_telefon_penyewa" value="{{ old('no_telefon_penyewa', $tempahanFasiliti->no_telefon_penyewa) }}" required maxlength="20" placeholder="01X-XXXXXXX" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('no_telefon_penyewa') border-red-500 @enderror">
                                @error('no_telefon_penyewa')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Emel</label>
                                <input type="email" name="emel_penyewa" value="{{ old('emel_penyewa', $tempahanFasiliti->emel_penyewa) }}" maxlength="255" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('emel_penyewa') border-red-500 @enderror">
                                @error('emel_penyewa')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Alamat 1 <span class="text-red-500">*</span></label>
                                <input type="text" name="alamat_penyewa_1" value="{{ old('alamat_penyewa_1', $tempahanFasiliti->alamat_penyewa_1) }}" required maxlength="255" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('alamat_penyewa_1') border-red-500 @enderror">
                                @error('alamat_penyewa_1')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Alamat 2</label>
                                <input type="text" name="alamat_penyewa_2" value="{{ old('alamat_penyewa_2', $tempahanFasiliti->alamat_penyewa_2) }}" maxlength="255" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Poskod <span class="text-red-500">*</span></label>
                                <input type="text" name="poskod_penyewa" value="{{ old('poskod_penyewa', $tempahanFasiliti->poskod_penyewa) }}" required maxlength="10" placeholder="00000" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('poskod_penyewa') border-red-500 @enderror">
                                @error('poskod_penyewa')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bandar <span class="text-red-500">*</span></label>
                                <input type="text" name="bandar_penyewa" value="{{ old('bandar_penyewa', $tempahanFasiliti->bandar_penyewa) }}" required maxlength="100" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('bandar_penyewa') border-red-500 @enderror">
                                @error('bandar_penyewa')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Negeri <span class="text-red-500">*</span></label>
                                <select name="negeri_penyewa" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('negeri_penyewa') border-red-500 @enderror">
                                    <option value="">Pilih Negeri</option>
                                    <option value="Johor" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="Kedah" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="Kelantan" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="Melaka" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="Negeri Sembilan" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="Pahang" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="Pulau Pinang" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                    <option value="Perak" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="Perlis" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="Sabah" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="Sarawak" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                    <option value="Selangor" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="Terengganu" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                    <option value="WP Kuala Lumpur" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'WP Kuala Lumpur' ? 'selected' : '' }}>WP Kuala Lumpur</option>
                                    <option value="WP Labuan" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'WP Labuan' ? 'selected' : '' }}>WP Labuan</option>
                                    <option value="WP Putrajaya" {{ old('negeri_penyewa', $tempahanFasiliti->negeri_penyewa) == 'WP Putrajaya' ? 'selected' : '' }}>WP Putrajaya</option>
                                </select>
                                @error('negeri_penyewa')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Organisasi</label>
                                <input type="text" name="organisasi_penyewa" value="{{ old('organisasi_penyewa', $tempahanFasiliti->organisasi_penyewa) }}" maxlength="255" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Tempahan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Tempahan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Tempahan <span class="text-red-500">*</span></label>
                                <input type="date" name="tarikh_tempahan" value="{{ old('tarikh_tempahan', $tempahanFasiliti->tarikh_tempahan ? $tempahanFasiliti->tarikh_tempahan->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('tarikh_tempahan') border-red-500 @enderror">
                                @error('tarikh_tempahan')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh & Masa Mula <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="tarikh_mula" id="tarikh_mula" value="{{ old('tarikh_mula', $tempahanFasiliti->tarikh_mula ? $tempahanFasiliti->tarikh_mula->format('Y-m-d\TH:i') : '') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('tarikh_mula') border-red-500 @enderror">
                                @error('tarikh_mula')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh & Masa Tamat <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="tarikh_tamat" id="tarikh_tamat" value="{{ old('tarikh_tamat', $tempahanFasiliti->tarikh_tamat ? $tempahanFasiliti->tarikh_tamat->format('Y-m-d\TH:i') : '') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('tarikh_tamat') border-red-500 @enderror">
                                @error('tarikh_tamat')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Unit Tempoh <span class="text-red-500">*</span></label>
                                <select name="unit_tempoh" id="unit_tempoh" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('unit_tempoh') border-red-500 @enderror">
                                    <option value="">Pilih Unit</option>
                                    <option value="Jam" {{ old('unit_tempoh', $tempahanFasiliti->unit_tempoh) == 'Jam' ? 'selected' : '' }}>Jam</option>
                                    <option value="Separuh Hari" {{ old('unit_tempoh', $tempahanFasiliti->unit_tempoh) == 'Separuh Hari' ? 'selected' : '' }}>Separuh Hari</option>
                                    <option value="Hari" {{ old('unit_tempoh', $tempahanFasiliti->unit_tempoh) == 'Hari' ? 'selected' : '' }}>Hari</option>
                                </select>
                                @error('unit_tempoh')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tempoh Sewa</label>
                                <input type="number" name="tempoh_sewa" id="tempoh_sewa" value="{{ old('tempoh_sewa', $tempahanFasiliti->tempoh_sewa) }}" readonly class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-gray-100">
                                <p class="text-[10px] text-gray-500 mt-1">Auto-calculated dari tarikh mula & tamat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2B: Item Fasiliti -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h2 class="text-sm font-semibold text-gray-900">Item Fasiliti</h2>
                            <button type="button" id="addItemBtn" class="h-[28px] px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 inline-flex items-center">
                                <span class="material-icons mr-1" style="font-size: 14px !important;">add</span>
                                Tambah Item
                            </button>
                        </div>

                        <!-- Header Row -->
                        <div class="hidden md:flex items-center gap-2 mb-2 px-2 py-2 bg-gray-200 rounded text-xs font-semibold text-gray-700">
                            <div style="flex: 1;">Pilih Fasiliti</div>
                            <div style="width: 96px;" class="text-center">Jumlah</div>
                            <div style="width: 112px;" class="text-right">Unit</div>
                            <div style="width: 112px;" class="text-right">Total</div>
                            <div style="width: 40px;" class="text-center">X</div>
                        </div>

                        <div id="itemsContainer" class="space-y-2">
                            @foreach($tempahanFasiliti->activeItems as $index => $item)
                            <div class="item-row bg-white p-2 rounded border border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <select name="items[{{ $index }}][fasiliti_id]" class="fasiliti-select w-full px-2 py-1.5 border border-gray-300 rounded text-xs" required>
                                            <option value="">Pilih Fasiliti</option>
                                            @foreach($fasilitiList as $fasiliti)
                                                <option value="{{ $fasiliti->id }}" 
                                                    data-harga-sejam="{{ $fasiliti->harga_sewa_sejam }}"
                                                    data-harga-separuh="{{ $fasiliti->harga_sewa_separuh_hari }}"
                                                    data-harga-sehari="{{ $fasiliti->harga_sewa_sehari }}"
                                                    data-deposit="{{ $fasiliti->deposit_diperlukan }}"
                                                    data-kuantiti="{{ $fasiliti->kuantiti_total }}"
                                                    data-countable="{{ $fasiliti->is_countable }}"
                                                    {{ $item->senarai_fasiliti_id == $fasiliti->id ? 'selected' : '' }}>
                                                    {{ $fasiliti->nama_fasiliti }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $item->id }}">
                                        <div class="availability-message text-[10px] mt-0.5"></div>
                                    </div>
                                    <div class="w-24">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" class="quantity-input w-full px-2 py-1.5 border border-gray-300 rounded text-xs text-center" required placeholder="Qty">
                                    </div>
                                    <div class="w-28">
                                        <input type="text" class="price-display w-full px-2 py-1.5 border border-gray-300 rounded text-xs bg-gray-50 text-right" value="RM {{ number_format($item->harga_per_unit, 2) }}" readonly>
                                    </div>
                                    <div class="w-28">
                                        <input type="text" class="subtotal-display w-full px-2 py-1.5 border border-gray-300 rounded text-xs bg-gray-100 font-semibold text-right" value="RM {{ number_format($item->subtotal, 2) }}" readonly>
                                    </div>
                                    <div class="w-10">
                                        <button type="button" class="remove-item-btn w-full h-[30px] bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center">
                                            <span class="material-icons" style="font-size: 16px !important;">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <p class="text-[10px] text-gray-500 mt-2">Sistem akan check availability secara real-time</p>
                    </div>

                    <!-- Section 3: Tujuan & Acara -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Tujuan & Acara</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tujuan Tempahan <span class="text-red-500">*</span></label>
                                <textarea name="tujuan_tempahan" required rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('tujuan_tempahan') border-red-500 @enderror">{{ old('tujuan_tempahan', $tempahanFasiliti->tujuan_tempahan) }}</textarea>
                                @error('tujuan_tempahan')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Acara</label>
                                <input type="text" name="jenis_acara" value="{{ old('jenis_acara', $tempahanFasiliti->jenis_acara) }}" maxlength="255" placeholder="Cth: Majlis Perkahwinan, Mesyuarat" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bilangan Jangka Peserta</label>
                                <input type="number" name="bilangan_jangka_peserta" value="{{ old('bilangan_jangka_peserta', $tempahanFasiliti->bilangan_jangka_peserta) }}" min="0" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Harga & Bayaran (Readonly) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Harga & Bayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Harga Sewa</label>
                                <input type="number" name="harga_sewa" id="harga_sewa" value="{{ old('harga_sewa', $tempahanFasiliti->harga_sewa) }}" step="0.01" readonly class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Deposit</label>
                                <input type="number" name="deposit" id="deposit" value="{{ old('deposit', $tempahanFasiliti->deposit) }}" step="0.01" readonly class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Bayaran</label>
                                <input type="number" name="jumlah_bayaran" id="jumlah_bayaran" value="{{ old('jumlah_bayaran', $tempahanFasiliti->jumlah_bayaran) }}" step="0.01" readonly class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-gray-100 font-semibold">
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2">Harga akan dikira secara automatik berdasarkan fasiliti dan tempoh sewa</p>
                    </div>

                    <!-- Section 5: Dokumen (Optional) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Dokumen (Optional)</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Surat Permohonan (PDF)</label>
                                <input type="file" name="surat_permohonan" accept=".pdf" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Salinan IC (PDF/JPG)</label>
                                <input type="file" name="salinan_ic" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF, JPG, PNG</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Surat Sokongan (PDF)</label>
                                <input type="file" name="surat_sokongan" accept=".pdf" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dokumen Lain (Max 3 files)</label>
                                <input type="file" name="dokumen_lain[]" accept=".pdf,.jpg,.jpeg,.png" multiple class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm bg-white focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB per file, format: PDF, JPG, PNG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Catatan</h2>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea name="catatan" rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">{{ old('catatan', $tempahanFasiliti->catatan) }}</textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('tempahan-fasiliti.index') }}" class="h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300 inline-flex items-center">
                            Batal
                        </a>
                        <button type="submit" class="h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 inline-flex items-center">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        let itemIndex = {{ $tempahanFasiliti->activeItems->count() }};

        // Add Item Button
        document.getElementById('addItemBtn').addEventListener('click', function() {
            const container = document.getElementById('itemsContainer');
            const newItem = createItemRow(itemIndex);
            container.insertAdjacentHTML('beforeend', newItem);
            itemIndex++;
            attachItemEvents();
        });

        function createItemRow(index) {
            return `
                <div class="item-row bg-white p-2 rounded border border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <select name="items[${index}][fasiliti_id]" class="fasiliti-select w-full px-2 py-1.5 border border-gray-300 rounded text-xs" required>
                                <option value="">Pilih Fasiliti</option>
                                @foreach($fasilitiList as $fasiliti)
                                    <option value="{{ $fasiliti->id }}" 
                                        data-harga-sejam="{{ $fasiliti->harga_sewa_sejam }}"
                                        data-harga-separuh="{{ $fasiliti->harga_sewa_separuh_hari }}"
                                        data-harga-sehari="{{ $fasiliti->harga_sewa_sehari }}"
                                        data-deposit="{{ $fasiliti->deposit_diperlukan }}"
                                        data-kuantiti="{{ $fasiliti->kuantiti_total }}"
                                        data-countable="{{ $fasiliti->is_countable }}">
                                        {{ $fasiliti->nama_fasiliti }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="availability-message text-[10px] mt-0.5"></div>
                        </div>
                        <div class="w-24">
                            <input type="number" name="items[${index}][quantity]" value="1" min="1" class="quantity-input w-full px-2 py-1.5 border border-gray-300 rounded text-xs text-center" required placeholder="Qty">
                        </div>
                        <div class="w-28">
                            <input type="text" class="price-display w-full px-2 py-1.5 border border-gray-300 rounded text-xs bg-gray-50 text-right" value="RM 0.00" readonly>
                        </div>
                        <div class="w-28">
                            <input type="text" class="subtotal-display w-full px-2 py-1.5 border border-gray-300 rounded text-xs bg-gray-100 font-semibold text-right" value="RM 0.00" readonly>
                        </div>
                        <div class="w-10">
                            <button type="button" class="remove-item-btn w-full h-[30px] bg-red-600 text-white rounded hover:bg-red-700 flex items-center justify-center">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function attachItemEvents() {
            // Remove item buttons
            document.querySelectorAll('.remove-item-btn').forEach(btn => {
                btn.onclick = function() {
                    if (document.querySelectorAll('.item-row').length > 1) {
                        this.closest('.item-row').remove();
                        calculateAllTotals();
                    } else {
                        alert('Minimum 1 item diperlukan');
                    }
                };
            });

            // Fasiliti change
            document.querySelectorAll('.fasiliti-select').forEach(select => {
                select.onchange = function() {
                    const row = this.closest('.item-row');
                    calculateItemPrice(row);
                    checkAvailability(row);
                };
            });

            // Quantity change
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.oninput = function() {
                    const row = this.closest('.item-row');
                    calculateItemPrice(row);
                    checkAvailability(row);
                };
            });
        }

        function calculateItemPrice(row) {
            const select = row.querySelector('.fasiliti-select');
            const quantityInput = row.querySelector('.quantity-input');
            const priceDisplay = row.querySelector('.price-display');
            const subtotalDisplay = row.querySelector('.subtotal-display');
            
            if (!select.value) return;
            
            const option = select.options[select.selectedIndex];
            const unitTempoh = document.getElementById('unit_tempoh').value;
            const tempohSewa = parseFloat(document.getElementById('tempoh_sewa').value) || 1;
            const quantity = parseInt(quantityInput.value) || 1;
            
            let hargaPerUnit = 0;
            if (unitTempoh === 'Jam') {
                hargaPerUnit = parseFloat(option.dataset.hargaSejam) || 0;
            } else if (unitTempoh === 'Separuh Hari') {
                hargaPerUnit = parseFloat(option.dataset.hargaSeparuh) || 0;
            } else if (unitTempoh === 'Hari') {
                hargaPerUnit = parseFloat(option.dataset.hargaSehari) || 0;
            }
            
            const subtotal = hargaPerUnit * quantity * tempohSewa;
            
            priceDisplay.value = 'RM ' + hargaPerUnit.toFixed(2);
            subtotalDisplay.value = 'RM ' + subtotal.toFixed(2);
            
            calculateAllTotals();
        }

        function calculateAllTotals() {
            let totalHargaSewa = 0;
            let totalDeposit = 0;
            
            document.querySelectorAll('.item-row').forEach(row => {
                const subtotalText = row.querySelector('.subtotal-display').value;
                const subtotal = parseFloat(subtotalText.replace('RM ', '').replace(',', '')) || 0;
                totalHargaSewa += subtotal;
                
                const select = row.querySelector('.fasiliti-select');
                if (select.value) {
                    const option = select.options[select.selectedIndex];
                    totalDeposit += parseFloat(option.dataset.deposit) || 0;
                }
            });
            
            document.getElementById('harga_sewa').value = totalHargaSewa.toFixed(2);
            document.getElementById('deposit').value = totalDeposit.toFixed(2);
            document.getElementById('jumlah_bayaran').value = (totalHargaSewa + totalDeposit).toFixed(2);
        }

        function checkAvailability(row) {
            const select = row.querySelector('.fasiliti-select');
            const quantityInput = row.querySelector('.quantity-input');
            const messageDiv = row.querySelector('.availability-message');
            
            if (!select.value) return;
            
            const fasilitiId = select.value;
            const quantity = parseInt(quantityInput.value) || 1;
            const tarikhMula = document.getElementById('tarikh_mula').value;
            const tarikhTamat = document.getElementById('tarikh_tamat').value;
            
            if (!tarikhMula || !tarikhTamat) {
                messageDiv.innerHTML = '<span class="text-orange-600">⚠️ Sila pilih tarikh mula & tamat</span>';
                return;
            }
            
            fetch(`/tempahan-fasiliti/check-availability?fasiliti_id=${fasilitiId}&tarikh_mula=${tarikhMula}&tarikh_tamat=${tarikhTamat}&exclude_tempahan_id={{ $tempahanFasiliti->id }}`)
                .then(res => res.json())
                .then(data => {
                    if (data.is_countable) {
                        if (data.available >= quantity) {
                            messageDiv.innerHTML = `<span class="text-green-600">✓ ${data.available} / ${data.total} tersedia</span>`;
                            quantityInput.max = data.available;
                        } else {
                            messageDiv.innerHTML = `<span class="text-red-600">✗ Hanya ${data.available} / ${data.total} tersedia</span>`;
                            quantityInput.max = data.available;
                        }
                    } else {
                        if (data.available > 0) {
                            messageDiv.innerHTML = `<span class="text-green-600">✓ Tersedia</span>`;
                        } else {
                            messageDiv.innerHTML = `<span class="text-red-600">✗ Tidak tersedia pada tarikh ini</span>`;
                        }
                    }
                });
        }

        function calculateTempoh() {
            const tarikhMula = new Date(document.getElementById('tarikh_mula').value);
            const tarikhTamat = new Date(document.getElementById('tarikh_tamat').value);
            const unitTempoh = document.getElementById('unit_tempoh').value;
            
            if (tarikhMula && tarikhTamat && tarikhTamat > tarikhMula) {
                const diffMs = tarikhTamat - tarikhMula;
                let tempoh = 0;
                
                if (unitTempoh === 'Jam') {
                    tempoh = Math.ceil(diffMs / (1000 * 60 * 60));
                } else if (unitTempoh === 'Separuh Hari') {
                    tempoh = Math.ceil(diffMs / (1000 * 60 * 60 * 12));
                } else if (unitTempoh === 'Hari') {
                    tempoh = Math.ceil(diffMs / (1000 * 60 * 60 * 24));
                }
                
                document.getElementById('tempoh_sewa').value = tempoh;
                
                // Recalculate all items
                document.querySelectorAll('.item-row').forEach(row => {
                    calculateItemPrice(row);
                    checkAvailability(row);
                });
            }
        }

        // Event listeners
        document.getElementById('unit_tempoh').addEventListener('change', function() {
            document.querySelectorAll('.item-row').forEach(row => calculateItemPrice(row));
        });
        document.getElementById('tarikh_mula').addEventListener('change', calculateTempoh);
        document.getElementById('tarikh_tamat').addEventListener('change', calculateTempoh);

        // Initialize
        attachItemEvents();
        document.querySelectorAll('.item-row').forEach(row => {
            calculateItemPrice(row);
            checkAvailability(row);
        });
    </script>
</body>
</html>
