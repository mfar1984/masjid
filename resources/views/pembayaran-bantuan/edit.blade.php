<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembayaran {{ $pembayaranBantuan->no_pembayaran }} - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Pembayaran {{ $pembayaranBantuan->no_pembayaran }}</h1>
                        <p class="text-xs text-gray-600">Kemaskini maklumat pembayaran bantuan</p>
                    </div>
                    <a href="{{ route('pembayaran-bantuan.show', $pembayaranBantuan) }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                        <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('pembayaran-bantuan.update', $pembayaranBantuan) }}">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Maklumat Pembayaran -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pembayaran</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tarikh_pembayaran" class="block text-xs font-medium text-gray-700 mb-2">Tarikh Pembayaran *</label>
                                <input type="date" id="tarikh_pembayaran" name="tarikh_pembayaran" value="{{ old('tarikh_pembayaran', $pembayaranBantuan->tarikh_pembayaran->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('tarikh_pembayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="jumlah_bayaran" class="block text-xs font-medium text-gray-700 mb-2">Jumlah Bayaran (RM) *</label>
                                <input type="number" step="0.01" id="jumlah_bayaran" name="jumlah_bayaran" value="{{ old('jumlah_bayaran', $pembayaranBantuan->jumlah_bayaran) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                @error('jumlah_bayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="kaedah_bayaran" class="block text-xs font-medium text-gray-700 mb-2">Kaedah Bayaran *</label>
                                <select id="kaedah_bayaran" name="kaedah_bayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="">-- Pilih Kaedah --</option>
                                    <option value="Tunai" {{ old('kaedah_bayaran', $pembayaranBantuan->kaedah_bayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Cek" {{ old('kaedah_bayaran', $pembayaranBantuan->kaedah_bayaran) == 'Cek' ? 'selected' : '' }}>Cek</option>
                                    <option value="Bank Transfer" {{ old('kaedah_bayaran', $pembayaranBantuan->kaedah_bayaran) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="Barangan" {{ old('kaedah_bayaran', $pembayaranBantuan->kaedah_bayaran) == 'Barangan' ? 'selected' : '' }}>Barangan</option>
                                    <option value="Baucar" {{ old('kaedah_bayaran', $pembayaranBantuan->kaedah_bayaran) == 'Baucar' ? 'selected' : '' }}>Baucar</option>
                                </select>
                                @error('kaedah_bayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>

                            <div>
                                <label for="status_pembayaran" class="block text-xs font-medium text-gray-700 mb-2">Status Pembayaran *</label>
                                <select id="status_pembayaran" name="status_pembayaran" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                                    <option value="Belum Bayar" {{ old('status_pembayaran', $pembayaranBantuan->status_pembayaran) == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="Sudah Bayar" {{ old('status_pembayaran', $pembayaranBantuan->status_pembayaran) == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                    <option value="Dibatalkan" {{ old('status_pembayaran', $pembayaranBantuan->status_pembayaran) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                @error('status_pembayaran')<span class="text-xs text-red-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('pembayaran-bantuan.show', $pembayaranBantuan) }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Kemaskini Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
