<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran Sewa - E-Masjid</title>
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
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Pembayaran Sewa</h1>
                    <p class="text-xs text-gray-600">Rekod pembayaran sewa fasiliti</p>
                </div>

                <form action="{{ route('pembayaran-sewa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Maklumat Pembayaran -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pembayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">No. Pembayaran</label>
                                <input type="text" value="Auto-generated" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100">
                                <p class="text-[10px] text-gray-500 mt-1">Akan dijana secara automatik</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Tempahan Fasiliti <span class="text-red-500">*</span></label>
                                <select name="tempahan_fasiliti_id" id="tempahan_select" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500 @error('tempahan_fasiliti_id') border-red-500 @enderror">
                                    <option value="">Pilih Tempahan</option>
                                    @foreach($tempahanList as $tempahan)
                                        <option value="{{ $tempahan->id }}" 
                                            data-fasiliti="{{ $tempahan->senariFasiliti->nama_fasiliti }}"
                                            data-penyewa="{{ $tempahan->nama_penyewa }}"
                                            data-harga="{{ $tempahan->harga_sewa }}"
                                            data-deposit="{{ $tempahan->deposit }}"
                                            data-jumlah="{{ $tempahan->jumlah_bayaran }}"
                                            {{ old('tempahan_fasiliti_id') == $tempahan->id ? 'selected' : '' }}>
                                            {{ $tempahan->senariFasiliti->nama_fasiliti }} - {{ $tempahan->nama_penyewa }} ({{ \Carbon\Carbon::parse($tempahan->tarikh_mula)->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tempahan_fasiliti_id')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Tarikh Pembayaran <span class="text-red-500">*</span></label>
                                <input type="date" name="tarikh_pembayaran" value="{{ old('tarikh_pembayaran', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500 @error('tarikh_pembayaran') border-red-500 @enderror">
                                @error('tarikh_pembayaran')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Kaedah Bayaran <span class="text-red-500">*</span></label>
                                <select name="kaedah_bayaran" id="kaedah_bayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500 @error('kaedah_bayaran') border-red-500 @enderror">
                                    <option value="">Pilih Kaedah</option>
                                    <option value="Tunai" {{ old('kaedah_bayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Cek" {{ old('kaedah_bayaran') == 'Cek' ? 'selected' : '' }}>Cek</option>
                                    <option value="Bank Transfer" {{ old('kaedah_bayaran') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Online Banking" {{ old('kaedah_bayaran') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                    <option value="E-Wallet" {{ old('kaedah_bayaran') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                </select>
                                @error('kaedah_bayaran')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Jumlah Sewa</label>
                                <input type="number" id="jumlah_sewa" value="{{ old('jumlah_sewa', 0) }}" step="0.01" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Jumlah Deposit</label>
                                <input type="number" id="jumlah_deposit" value="{{ old('jumlah_deposit', 0) }}" step="0.01" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-2">Jumlah Bayaran</label>
                                <input type="number" id="jumlah_bayaran" value="{{ old('jumlah_bayaran', 0) }}" step="0.01" readonly class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs bg-gray-100 font-semibold">
                                <p class="text-[10px] text-gray-500 mt-1">Jumlah akan dipaparkan berdasarkan tempahan yang dipilih</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Bank (Conditional) -->
                    <div id="bank_section" class="bg-blue-50 rounded-lg p-4 mb-6 hidden">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bank</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Nama Bank <span class="text-red-500">*</span></label>
                                <select name="nama_bank" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Bank</option>
                                    <option value="Maybank" {{ old('nama_bank') == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                    <option value="CIMB Bank" {{ old('nama_bank') == 'CIMB Bank' ? 'selected' : '' }}>CIMB Bank</option>
                                    <option value="Public Bank" {{ old('nama_bank') == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                                    <option value="RHB Bank" {{ old('nama_bank') == 'RHB Bank' ? 'selected' : '' }}>RHB Bank</option>
                                    <option value="Hong Leong Bank" {{ old('nama_bank') == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                                    <option value="AmBank" {{ old('nama_bank') == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                                    <option value="Bank Islam" {{ old('nama_bank') == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                                    <option value="Bank Muamalat" {{ old('nama_bank') == 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                    <option value="Bank Rakyat" {{ old('nama_bank') == 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                                    <option value="BSN" {{ old('nama_bank') == 'BSN' ? 'selected' : '' }}>BSN</option>
                                    <option value="Affin Bank" {{ old('nama_bank') == 'Affin Bank' ? 'selected' : '' }}>Affin Bank</option>
                                    <option value="Alliance Bank" {{ old('nama_bank') == 'Alliance Bank' ? 'selected' : '' }}>Alliance Bank</option>
                                    <option value="OCBC Bank" {{ old('nama_bank') == 'OCBC Bank' ? 'selected' : '' }}>OCBC Bank</option>
                                    <option value="Standard Chartered" {{ old('nama_bank') == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                    <option value="HSBC Bank" {{ old('nama_bank') == 'HSBC Bank' ? 'selected' : '' }}>HSBC Bank</option>
                                    <option value="UOB Bank" {{ old('nama_bank') == 'UOB Bank' ? 'selected' : '' }}>UOB Bank</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">No. Rujukan <span class="text-red-500">*</span></label>
                                <input type="text" name="no_rujukan" value="{{ old('no_rujukan') }}" maxlength="100" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">No. Akaun</label>
                                <input type="text" name="no_akaun" value="{{ old('no_akaun') }}" maxlength="50" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Maklumat Cek (Conditional) -->
                    <div id="cek_section" class="bg-blue-50 rounded-lg p-4 mb-6 hidden">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Cek</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">No. Cek <span class="text-red-500">*</span></label>
                                <input type="text" name="no_cek" value="{{ old('no_cek') }}" maxlength="50" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Tarikh Cek <span class="text-red-500">*</span></label>
                                <input type="date" name="tarikh_cek" value="{{ old('tarikh_cek') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Nama Bank <span class="text-red-500">*</span></label>
                                <select name="nama_bank_cek" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Bank</option>
                                    <option value="Maybank" {{ old('nama_bank_cek') == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                    <option value="CIMB Bank" {{ old('nama_bank_cek') == 'CIMB Bank' ? 'selected' : '' }}>CIMB Bank</option>
                                    <option value="Public Bank" {{ old('nama_bank_cek') == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                                    <option value="RHB Bank" {{ old('nama_bank_cek') == 'RHB Bank' ? 'selected' : '' }}>RHB Bank</option>
                                    <option value="Hong Leong Bank" {{ old('nama_bank_cek') == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                                    <option value="AmBank" {{ old('nama_bank_cek') == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                                    <option value="Bank Islam" {{ old('nama_bank_cek') == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                                    <option value="Bank Muamalat" {{ old('nama_bank_cek') == 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                    <option value="Bank Rakyat" {{ old('nama_bank_cek') == 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                                    <option value="BSN" {{ old('nama_bank_cek') == 'BSN' ? 'selected' : '' }}>BSN</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Dokumen Pembayaran (Optional) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Dokumen Pembayaran (Optional)</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Resit Pembayaran (PDF/JPG)</label>
                                <input type="file" name="resit_pembayaran" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF, JPG, PNG</p>
                            </div>

                            <div id="bukti_transfer_field" class="hidden">
                                <label class="block text-xs font-medium text-gray-700 mb-2">Bukti Transfer (PDF/JPG)</label>
                                <input type="file" name="bukti_transfer" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF, JPG, PNG</p>
                            </div>

                            <div id="salinan_cek_field" class="hidden">
                                <label class="block text-xs font-medium text-gray-700 mb-2">Salinan Cek (PDF/JPG)</label>
                                <input type="file" name="salinan_cek" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF, JPG, PNG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Status & Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Status & Catatan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Status Pembayaran <span class="text-red-500">*</span></label>
                                <select name="status_pembayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500 @error('status_pembayaran') border-red-500 @enderror">
                                    <option value="Belum Bayar" {{ old('status_pembayaran') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="Sudah Bayar" {{ old('status_pembayaran', 'Sudah Bayar') == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                </select>
                                @error('status_pembayaran')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:ring-blue-500 focus:border-blue-500">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('pembayaran-sewa.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
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
        // Auto-populate jumlah from tempahan
        document.getElementById('tempahan_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                document.getElementById('jumlah_sewa').value = parseFloat(selectedOption.dataset.harga || 0).toFixed(2);
                document.getElementById('jumlah_deposit').value = parseFloat(selectedOption.dataset.deposit || 0).toFixed(2);
                document.getElementById('jumlah_bayaran').value = parseFloat(selectedOption.dataset.jumlah || 0).toFixed(2);
            } else {
                document.getElementById('jumlah_sewa').value = '0.00';
                document.getElementById('jumlah_deposit').value = '0.00';
                document.getElementById('jumlah_bayaran').value = '0.00';
            }
        });

        // Show/hide Bank and Cek sections based on kaedah_bayaran
        document.getElementById('kaedah_bayaran').addEventListener('change', function() {
            const kaedah = this.value;
            const bankSection = document.getElementById('bank_section');
            const cekSection = document.getElementById('cek_section');
            const buktiTransferField = document.getElementById('bukti_transfer_field');
            const salinanCekField = document.getElementById('salinan_cek_field');
            
            // Hide all conditional sections first
            bankSection.classList.add('hidden');
            cekSection.classList.add('hidden');
            buktiTransferField.classList.add('hidden');
            salinanCekField.classList.add('hidden');
            
            // Show relevant sections based on kaedah
            if (kaedah === 'Bank Transfer' || kaedah === 'Online Banking') {
                bankSection.classList.remove('hidden');
                buktiTransferField.classList.remove('hidden');
            } else if (kaedah === 'Cek') {
                cekSection.classList.remove('hidden');
                salinanCekField.classList.remove('hidden');
            }
        });

        // Trigger on page load if old value exists
        window.addEventListener('DOMContentLoaded', function() {
            const kaedahSelect = document.getElementById('kaedah_bayaran');
            if (kaedahSelect.value) {
                kaedahSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
