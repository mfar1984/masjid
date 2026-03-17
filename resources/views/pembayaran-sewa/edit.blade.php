<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Pembayaran Sewa - E-Masjid</title>
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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Kemaskini Pembayaran Sewa</h1>
                    <p class="text-xs text-gray-600">Kemaskini maklumat pembayaran sewa fasiliti</p>
                </div>

                <form action="{{ route('pembayaran-sewa.update', $pembayaranSewa) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Pembayaran -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pembayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Pembayaran</label>
                                <input type="text" value="{{ $pembayaranSewa->no_pembayaran }}" readonly class="w-full text-xs border-gray-300 rounded-sm bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tempahan Fasiliti</label>
                                <input type="text" value="{{ $pembayaranSewa->tempahanFasiliti->senariFasiliti->nama_fasiliti }} - {{ $pembayaranSewa->tempahanFasiliti->nama_penyewa }}" readonly class="w-full text-xs border-gray-300 rounded-sm bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Pembayaran <span class="text-red-500">*</span></label>
                                <input type="date" name="tarikh_pembayaran" value="{{ old('tarikh_pembayaran', $pembayaranSewa->tarikh_pembayaran->format('Y-m-d')) }}" required class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('tarikh_pembayaran') border-red-500 @enderror">
                                @error('tarikh_pembayaran')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Kaedah Bayaran <span class="text-red-500">*</span></label>
                                <select name="kaedah_bayaran" id="kaedah_bayaran" required class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('kaedah_bayaran') border-red-500 @enderror">
                                    <option value="">Pilih Kaedah</option>
                                    <option value="Tunai" {{ old('kaedah_bayaran', $pembayaranSewa->kaedah_bayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Cek" {{ old('kaedah_bayaran', $pembayaranSewa->kaedah_bayaran) == 'Cek' ? 'selected' : '' }}>Cek</option>
                                    <option value="Bank Transfer" {{ old('kaedah_bayaran', $pembayaranSewa->kaedah_bayaran) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Online Banking" {{ old('kaedah_bayaran', $pembayaranSewa->kaedah_bayaran) == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                    <option value="E-Wallet" {{ old('kaedah_bayaran', $pembayaranSewa->kaedah_bayaran) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                </select>
                                @error('kaedah_bayaran')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Sewa</label>
                                <input type="number" value="{{ $pembayaranSewa->jumlah_sewa }}" step="0.01" readonly class="w-full text-xs border-gray-300 rounded-sm bg-gray-100">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Deposit</label>
                                <input type="number" value="{{ $pembayaranSewa->jumlah_deposit }}" step="0.01" readonly class="w-full text-xs border-gray-300 rounded-sm bg-gray-100">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Bayaran</label>
                                <input type="number" value="{{ $pembayaranSewa->jumlah_bayaran }}" step="0.01" readonly class="w-full text-xs border-gray-300 rounded-sm bg-gray-100 font-semibold text-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Maklumat Bank (Conditional) -->
                    <div id="bank_section" class="bg-blue-50 rounded-lg p-4 mb-6 {{ in_array($pembayaranSewa->kaedah_bayaran, ['Bank Transfer', 'Online Banking']) ? '' : 'hidden' }}">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Bank</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bank <span class="text-red-500">*</span></label>
                                <select name="nama_bank" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Bank</option>
                                    <option value="Maybank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                    <option value="CIMB Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'CIMB Bank' ? 'selected' : '' }}>CIMB Bank</option>
                                    <option value="Public Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                                    <option value="RHB Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'RHB Bank' ? 'selected' : '' }}>RHB Bank</option>
                                    <option value="Hong Leong Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                                    <option value="AmBank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                                    <option value="Bank Islam" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                                    <option value="Bank Muamalat" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                    <option value="Bank Rakyat" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                                    <option value="BSN" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'BSN' ? 'selected' : '' }}>BSN</option>
                                    <option value="Affin Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Affin Bank' ? 'selected' : '' }}>Affin Bank</option>
                                    <option value="Alliance Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Alliance Bank' ? 'selected' : '' }}>Alliance Bank</option>
                                    <option value="OCBC Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'OCBC Bank' ? 'selected' : '' }}>OCBC Bank</option>
                                    <option value="Standard Chartered" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                    <option value="HSBC Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'HSBC Bank' ? 'selected' : '' }}>HSBC Bank</option>
                                    <option value="UOB Bank" {{ old('nama_bank', $pembayaranSewa->nama_bank) == 'UOB Bank' ? 'selected' : '' }}>UOB Bank</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Rujukan <span class="text-red-500">*</span></label>
                                <input type="text" name="no_rujukan" value="{{ old('no_rujukan', $pembayaranSewa->no_rujukan) }}" maxlength="100" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Akaun</label>
                                <input type="text" name="no_akaun" value="{{ old('no_akaun', $pembayaranSewa->no_akaun) }}" maxlength="50" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Maklumat Cek (Conditional) -->
                    <div id="cek_section" class="bg-blue-50 rounded-lg p-4 mb-6 {{ $pembayaranSewa->kaedah_bayaran == 'Cek' ? '' : 'hidden' }}">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Cek</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">No. Cek <span class="text-red-500">*</span></label>
                                <input type="text" name="no_cek" value="{{ old('no_cek', $pembayaranSewa->no_cek) }}" maxlength="50" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Cek <span class="text-red-500">*</span></label>
                                <input type="date" name="tarikh_cek" value="{{ old('tarikh_cek', $pembayaranSewa->tarikh_cek ? $pembayaranSewa->tarikh_cek->format('Y-m-d') : '') }}" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bank <span class="text-red-500">*</span></label>
                                <select name="nama_bank_cek" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Pilih Bank</option>
                                    <option value="Maybank" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                    <option value="CIMB Bank" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'CIMB Bank' ? 'selected' : '' }}>CIMB Bank</option>
                                    <option value="Public Bank" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                                    <option value="RHB Bank" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'RHB Bank' ? 'selected' : '' }}>RHB Bank</option>
                                    <option value="Hong Leong Bank" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                                    <option value="AmBank" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                                    <option value="Bank Islam" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                                    <option value="Bank Muamalat" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                                    <option value="Bank Rakyat" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                                    <option value="BSN" {{ old('nama_bank_cek', $pembayaranSewa->nama_bank) == 'BSN' ? 'selected' : '' }}>BSN</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Dokumen Pembayaran (Optional) -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Dokumen Pembayaran (Optional)</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Resit Pembayaran (PDF/JPG)</label>
                                @if($pembayaranSewa->resit_pembayaran_path)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($pembayaranSewa->resit_pembayaran_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            <span class="material-icons text-sm align-middle">description</span> Lihat Dokumen Semasa
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="resit_pembayaran" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF, JPG, PNG</p>
                            </div>

                            <div id="bukti_transfer_field" class="{{ in_array($pembayaranSewa->kaedah_bayaran, ['Bank Transfer', 'Online Banking']) ? '' : 'hidden' }}">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bukti Transfer (PDF/JPG)</label>
                                @if($pembayaranSewa->bukti_transfer_path)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($pembayaranSewa->bukti_transfer_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            <span class="material-icons text-sm align-middle">description</span> Lihat Dokumen Semasa
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="bukti_transfer" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF, JPG, PNG</p>
                            </div>

                            <div id="salinan_cek_field" class="{{ $pembayaranSewa->kaedah_bayaran == 'Cek' ? '' : 'hidden' }}">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Salinan Cek (PDF/JPG)</label>
                                @if($pembayaranSewa->salinan_cek_path)
                                    <div class="mb-2">
                                        <a href="{{ Storage::url($pembayaranSewa->salinan_cek_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            <span class="material-icons text-sm align-middle">description</span> Lihat Dokumen Semasa
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="salinan_cek" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max 5MB, format: PDF, JPG, PNG</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Deposit Return (Show only on edit) -->
                    @if($pembayaranSewa->status_pembayaran == 'Sudah Bayar' && $pembayaranSewa->jumlah_deposit > 0)
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Pulangan Deposit</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Deposit Dikembalikan</label>
                                <input type="number" name="deposit_dikembalikan" value="{{ old('deposit_dikembalikan', $pembayaranSewa->deposit_dikembalikan) }}" step="0.01" min="0" max="{{ $pembayaranSewa->jumlah_deposit }}" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-[10px] text-gray-500 mt-1">Max: RM {{ number_format($pembayaranSewa->jumlah_deposit, 2) }}</p>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Kembalikan Deposit</label>
                                <input type="date" name="tarikh_kembalikan_deposit" value="{{ old('tarikh_kembalikan_deposit', $pembayaranSewa->tarikh_kembalikan_deposit ? $pembayaranSewa->tarikh_kembalikan_deposit->format('Y-m-d') : '') }}" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Potongan Deposit (jika ada)</label>
                                <textarea name="sebab_potongan_deposit" rows="2" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">{{ old('sebab_potongan_deposit', $pembayaranSewa->sebab_potongan_deposit) }}</textarea>
                                <p class="text-[10px] text-gray-500 mt-1">Isi jika deposit dikembalikan kurang dari jumlah asal</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Section 6: Status & Catatan -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Status & Catatan</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status Pembayaran <span class="text-red-500">*</span></label>
                                <select name="status_pembayaran" required class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500 @error('status_pembayaran') border-red-500 @enderror">
                                    <option value="Belum Bayar" {{ old('status_pembayaran', $pembayaranSewa->status_pembayaran) == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="Sudah Bayar" {{ old('status_pembayaran', $pembayaranSewa->status_pembayaran) == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                    <option value="Deposit Dikembalikan" {{ old('status_pembayaran', $pembayaranSewa->status_pembayaran) == 'Deposit Dikembalikan' ? 'selected' : '' }}>Deposit Dikembalikan</option>
                                    <option value="Dibatalkan" {{ old('status_pembayaran', $pembayaranSewa->status_pembayaran) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                @error('status_pembayaran')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea name="catatan" rows="3" class="w-full text-xs border-gray-300 rounded-sm focus:ring-blue-500 focus:border-blue-500">{{ old('catatan', $pembayaranSewa->catatan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('pembayaran-sewa.show', $pembayaranSewa) }}" class="h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300 inline-flex items-center">
                            Batal
                        </a>
                        <button type="submit" class="h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 inline-flex items-center">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Kemaskini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
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
    </script>
</body>
</html>
