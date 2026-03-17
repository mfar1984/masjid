<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mohon Akses - {{ $item->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Header -->
        <div class="bg-white rounded shadow-sm p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                @if($type === 'folder')
                    <span class="material-icons text-4xl text-yellow-500">folder</span>
                @else
                    <span class="material-icons text-4xl text-blue-500">description</span>
                @endif
                
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $item->name }}</h1>
                    <p class="text-sm text-gray-600">Dokumen Terhad</p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                <div class="flex items-start gap-3">
                    <span class="material-icons text-yellow-600 mt-0.5">lock</span>
                    <div>
                        <h3 class="font-medium text-yellow-800 mb-1">Akses Terhad</h3>
                        <p class="text-sm text-yellow-700">
                            Dokumen ini memerlukan kebenaran khas untuk diakses. 
                            Sila isi borang di bawah untuk memohon akses.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Access Form -->
        <div class="bg-white rounded shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Mohon Akses Dokumen</h2>
            
            <form id="requestAccessForm" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="item_type" value="{{ $type }}">
                <input type="hidden" name="item_id" value="{{ $item->hash_token }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Masjid Anda
                    </label>
                    <input type="text" 
                           name="masjid_name" 
                           value="{{ Auth::user()->masjid->nama ?? '' }}" 
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kod Masjid
                    </label>
                    <input type="text" 
                           name="kod_masjid" 
                           value="{{ Auth::user()->masjid->kod_masjid ?? '' }}" 
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sebab Permohonan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              rows="4" 
                              required
                              placeholder="Sila nyatakan sebab anda memerlukan akses kepada dokumen ini..."
                              class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Akses Diperlukan
                    </label>
                    <select name="requested_permission" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="view">Lihat sahaja</option>
                        <option value="comment">Lihat dan komen</option>
                        <option value="edit">Lihat, komen dan edit</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        <span class="material-icons text-sm mr-2">send</span>
                        Hantar Permohonan
                    </button>
                    
                    <a href="{{ url('/') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="bg-blue-50 border border-blue-200 rounded p-4 mt-6">
            <div class="flex items-start gap-3">
                <span class="material-icons text-blue-600 mt-0.5">info</span>
                <div>
                    <h3 class="font-medium text-blue-800 mb-1">Maklumat</h3>
                    <p class="text-sm text-blue-700">
                        Permohonan anda akan dihantar kepada pemilik dokumen dan pentadbir sistem. 
                        Anda akan menerima notifikasi sebaik sahaja permohonan anda diproses.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('requestAccessForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="material-icons text-sm mr-2">hourglass_empty</span>Menghantar...';
            
            try {
                const response = await fetch('/api/documents/sharing/request-access', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success message
                    document.body.innerHTML = `
                        <div class="container mx-auto px-4 py-8 max-w-2xl">
                            <div class="bg-white rounded shadow-sm p-6 text-center">
                                <span class="material-icons text-6xl text-green-500 mb-4">check_circle</span>
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">Permohonan Berjaya Dihantar</h2>
                                <p class="text-gray-600 mb-4">
                                    Permohonan akses anda telah dihantar kepada pemilik dokumen. 
                                    Anda akan menerima notifikasi sebaik sahaja permohonan diproses.
                                </p>
                                <a href="${window.location.origin}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                    Kembali ke Laman Utama
                                </a>
                            </div>
                        </div>
                    `;
                } else {
                    alert('Ralat: ' + (data.message || 'Gagal menghantar permohonan'));
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<span class="material-icons text-sm mr-2">send</span>Hantar Permohonan';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ralat sistem. Sila cuba lagi.');
                submitButton.disabled = false;
                submitButton.innerHTML = '<span class="material-icons text-sm mr-2">send</span>Hantar Permohonan';
            }
        });
    </script>
</body>
</html>
