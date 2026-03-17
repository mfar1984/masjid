<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran Bantuan - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Pembayaran Bantuan</h1>
                        <p class="text-xs text-gray-600">Cipta rekod pembayaran bantuan baharu</p>
                    </div>
                    <a href="{{ route('pembayaran-bantuan.index') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('pembayaran-bantuan.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Maklumat Permohonan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Permohonan</h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="permohonan_bantuan_id" class="block text-xs font-medium text-gray-700 mb-2">Pilih Permohonan Yang Diluluskan *</label>
                                <select id="permohonan_bantuan_id" name="permohonan_bantuan_id" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" onchange="updatePermohonanDetails(this)">
                                    <option value="">-- Pilih Permohonan --</option>
                                    @foreach($permohonan as $p)
                                        <option value="{{ $p->id }}" 
                                                data-penerima="{{ $p->penerimaBantuan->nama_penuh }}"
                                                data-program="{{ $p->programKebajikan->nama_program }}"
                                                data-jumlah="{{ $p->jumlah_diluluskan }}"
                                                {{ old('permohonan_bantuan_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->no_permohonan }} - {{ $p->penerimaBantuan->nama_penuh }} (RM {{ number_format($p->jumlah_diluluskan, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('permohonan_bantuan_id')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div id="permohonan-details" class="hidden bg-white rounded p-3 border border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <span class="font-medium text-gray-700">Penerima:</span>
                                        <span id="detail-penerima" class="text-gray-900">-</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Program:</span>
                                        <span id="detail-program" class="text-gray-900">-</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Jumlah Diluluskan:</span>
                                        <span id="detail-jumlah" class="text-gray-900 font-semibold">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Pembayaran -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pembayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tarikh_pembayaran" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Pembayaran *</label>
                                <input type="date" id="tarikh_pembayaran" name="tarikh_pembayaran" value="{{ old('tarikh_pembayaran', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_pembayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jumlah_bayaran" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Bayaran (RM) *</label>
                                <input type="number" step="0.01" id="jumlah_bayaran" name="jumlah_bayaran" value="{{ old('jumlah_bayaran') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jumlah_bayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="kaedah_bayaran" class="block text-xs font-medium text-gray-700 mb-2">Kaedah Bayaran *</label>
                                <select id="kaedah_bayaran" name="kaedah_bayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" onchange="togglePaymentFields(this.value)">
                                    <option value="">-- Pilih Kaedah --</option>
                                    <option value="Tunai" {{ old('kaedah_bayaran', $settings['default_payment_method'] ?? 'Tunai') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Cek" {{ old('kaedah_bayaran', $settings['default_payment_method'] ?? '') == 'Cek' ? 'selected' : '' }}>Cek</option>
                                    <option value="Bank Transfer" {{ old('kaedah_bayaran', $settings['default_payment_method'] ?? '') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Barangan" {{ old('kaedah_bayaran', $settings['default_payment_method'] ?? '') == 'Barangan' ? 'selected' : '' }}>Barangan</option>
                                    <option value="Baucar" {{ old('kaedah_bayaran', $settings['default_payment_method'] ?? '') == 'Baucar' ? 'selected' : '' }}>Baucar</option>
                                </select>
                                @if(isset($settings['default_payment_method']) && $settings['default_payment_method'])
                                <p class="text-[10px] text-gray-500 mt-1">
                                    <span class="material-icons text-xs align-middle">info</span>
                                    Kaedah lalai: {{ $settings['default_payment_method'] }}
                                </p>
                                @endif
                                @error('kaedah_bayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Maklumat Bank/Cek (Conditional) -->
                    <div id="bank-fields" class="bg-blue-50 rounded-lg p-4 mb-6 hidden">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bank</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_bank" class="block text-xs font-medium text-gray-700 mb-2">Nama Bank *</label>
                                <input type="text" id="nama_bank" name="nama_bank" value="{{ old('nama_bank') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nama_bank')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div id="no-akaun-field">
                                <label for="no_akaun" class="block text-xs font-medium text-gray-700 mb-2">No. Akaun *</label>
                                <input type="text" id="no_akaun" name="no_akaun" value="{{ old('no_akaun') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_akaun')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div id="no-rujukan-field">
                                <label for="no_rujukan" class="block text-xs font-medium text-gray-700 mb-2">No. Rujukan *</label>
                                <input type="text" id="no_rujukan" name="no_rujukan" value="{{ old('no_rujukan') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_rujukan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Maklumat Cek (Conditional) -->
                    <div id="cek-fields" class="bg-blue-50 rounded-lg p-4 mb-6 hidden">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Cek</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="no_cek" class="block text-xs font-medium text-gray-700 mb-2">No. Cek *</label>
                                <input type="text" id="no_cek" name="no_cek" value="{{ old('no_cek') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('no_cek')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="tarikh_cek" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Cek *</label>
                                <input type="date" id="tarikh_cek" name="tarikh_cek" value="{{ old('tarikh_cek') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_cek')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Maklumat Barangan (Conditional) -->
                    <div id="barangan-fields" class="bg-blue-50 rounded-lg p-4 mb-6 hidden">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Barangan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="senarai_barangan" class="block text-xs font-medium text-gray-700 mb-2">Senarai Barangan *</label>
                                <textarea id="senarai_barangan" name="senarai_barangan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">{{ old('senarai_barangan') }}</textarea>
                                @error('senarai_barangan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="nilai_barangan" class="block text-xs font-medium text-gray-700 mb-2">Nilai Barangan (RM) *</label>
                                <input type="number" step="0.01" id="nilai_barangan" name="nilai_barangan" value="{{ old('nilai_barangan') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('nilai_barangan')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('pembayaran-bantuan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />

    <script>
        function updatePermohonanDetails(select) {
            const option = select.options[select.selectedIndex];
            const detailsDiv = document.getElementById('permohonan-details');
            
            if (option.value) {
                document.getElementById('detail-penerima').textContent = option.dataset.penerima;
                document.getElementById('detail-program').textContent = option.dataset.program;
                document.getElementById('detail-jumlah').textContent = 'RM ' + parseFloat(option.dataset.jumlah).toFixed(2);
                document.getElementById('jumlah_bayaran').value = option.dataset.jumlah;
                detailsDiv.classList.remove('hidden');
            } else {
                detailsDiv.classList.add('hidden');
            }
        }

        function togglePaymentFields(kaedah) {
            const bankFields = document.getElementById('bank-fields');
            const cekFields = document.getElementById('cek-fields');
            const baranganFields = document.getElementById('barangan-fields');
            const noAkaunField = document.getElementById('no-akaun-field');
            const noRujukanField = document.getElementById('no-rujukan-field');
            
            // Hide all
            bankFields.classList.add('hidden');
            cekFields.classList.add('hidden');
            baranganFields.classList.add('hidden');
            
            // Show based on selection
            if (kaedah === 'Bank Transfer') {
                bankFields.classList.remove('hidden');
                noAkaunField.classList.remove('hidden');
                noRujukanField.classList.remove('hidden');
            } else if (kaedah === 'Cek') {
                bankFields.classList.remove('hidden');
                cekFields.classList.remove('hidden');
                noAkaunField.classList.add('hidden');
                noRujukanField.classList.add('hidden');
            } else if (kaedah === 'Barangan') {
                baranganFields.classList.remove('hidden');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const kaedahSelect = document.getElementById('kaedah_bayaran');
            if (kaedahSelect.value) {
                togglePaymentFields(kaedahSelect.value);
            }
            
            const permohonanSelect = document.getElementById('permohonan_bantuan_id');
            if (permohonanSelect.value) {
                updatePermohonanDetails(permohonanSelect);
            }
        });
    </script>
</body>
</html>
