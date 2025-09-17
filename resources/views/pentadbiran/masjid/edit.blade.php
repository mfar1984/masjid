<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Kemaskini Masjid - E-Masjid' }}</title>
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
                        <h1 class="text-lg font-bold text-gray-900 mb-1">Kemaskini Masjid</h1>
                        <p class="text-2xs text-gray-600">Kemaskini maklumat {{ $masjid->nama }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('senarai-masjid.index') }}" 
                           class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-gray-100 text-gray-700 btn-text rounded hover:bg-gray-200">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('senarai-masjid.update', $masjid) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Asas</h3>

                        <x-forms.input-field
                            label="Nombor Daftar"
                            name="nombor_daftar"
                            placeholder="Contoh: REG/2024/001"
                            :error="$errors->first('nombor_daftar')"
                            help="Nombor pendaftaran rasmi (jika ada)"
                            :value="old('nombor_daftar', $masjid->nombor_daftar)"
                        />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-forms.input-field
                                label="Nama Masjid"
                                name="nama"
                                placeholder="Contoh: Masjid Al-Hidayah"
                                required="true"
                                :error="$errors->first('nama')"
                                help="Nama pendek masjid"
                                :value="old('nama', $masjid->nama)"
                            />

                            <x-forms.input-field
                                label="Nama Penuh"
                                name="nama_penuh"
                                placeholder="Contoh: Masjid Al-Hidayah Taman Desa"
                                :error="$errors->first('nama_penuh')"
                                help="Nama lengkap dengan lokasi"
                                :value="old('nama_penuh', $masjid->nama_penuh)"
                            />
                        </div>

                        <x-forms.input-field
                            label="Kategori"
                            name="kategori"
                            type="select"
                            required="true"
                            :error="$errors->first('kategori')"
                        >
                            <option value="">Pilih Kategori</option>
                            <option value="masjid" {{ old('kategori', $masjid->kategori) == 'masjid' ? 'selected' : '' }}>Masjid</option>
                            <option value="surau" {{ old('kategori', $masjid->kategori) == 'surau' ? 'selected' : '' }}>Surau</option>
                            <option value="musolla" {{ old('kategori', $masjid->kategori) == 'musolla' ? 'selected' : '' }}>Musolla</option>
                        </x-forms.input-field>
                    </div>

                    <!-- Location Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Lokasi</h3>
                        
                        <x-forms.input-field
                            label="Alamat"
                            name="alamat"
                            type="textarea"
                            rows="3"
                            placeholder="Contoh: No. 123, Jalan Masjid, Taman Desa"
                            required="true"
                            :error="$errors->first('alamat')"
                            :value="old('alamat', $masjid->alamat)"
                        />
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-forms.input-field
                                label="Poskod"
                                name="poskod"
                                placeholder="Contoh: 50300"
                                maxlength="10"
                                :error="$errors->first('poskod')"
                                :value="old('poskod', $masjid->poskod)"
                            />
                            
                            <x-forms.input-field
                                label="Bandar"
                                name="bandar"
                                placeholder="Contoh: Kuala Lumpur"
                                :error="$errors->first('bandar')"
                                :value="old('bandar', $masjid->bandar)"
                            />
                            
                            <x-forms.input-field
                                label="Negeri"
                                name="negeri"
                                type="select"
                                required="true"
                                :error="$errors->first('negeri')"
                            >
                                <option value="">Pilih Negeri</option>
                                <optgroup label="Wilayah Persekutuan">
                                    <option value="Kuala Lumpur" {{ old('negeri', $masjid->negeri) == 'Kuala Lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                                    <option value="Putrajaya" {{ old('negeri', $masjid->negeri) == 'Putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                                    <option value="Labuan" {{ old('negeri', $masjid->negeri) == 'Labuan' ? 'selected' : '' }}>Labuan</option>
                                </optgroup>
                                <optgroup label="Semenanjung Malaysia">
                                    <option value="Johor" {{ old('negeri', $masjid->negeri) == 'Johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="Kedah" {{ old('negeri', $masjid->negeri) == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="Kelantan" {{ old('negeri', $masjid->negeri) == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="Melaka" {{ old('negeri', $masjid->negeri) == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="Negeri Sembilan" {{ old('negeri', $masjid->negeri) == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="Pahang" {{ old('negeri', $masjid->negeri) == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="Perak" {{ old('negeri', $masjid->negeri) == 'Perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="Perlis" {{ old('negeri', $masjid->negeri) == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="Pulau Pinang" {{ old('negeri', $masjid->negeri) == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                    <option value="Selangor" {{ old('negeri', $masjid->negeri) == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="Terengganu" {{ old('negeri', $masjid->negeri) == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                </optgroup>
                                <optgroup label="Sabah & Sarawak">
                                    <option value="Sabah" {{ old('negeri', $masjid->negeri) == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="Sarawak" {{ old('negeri', $masjid->negeri) == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                </optgroup>
                            </x-forms.input-field>
                        </div>

                        <!-- Latitude and Longitude Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-forms.input-field
                                label="Latitude"
                                name="latitude"
                                placeholder="Akan dikemas kini dari peta"
                                required="true"
                                readonly="true"
                                :error="$errors->first('latitude')"
                                help="Koordinat latitude (dikemas kini secara automatik dari peta)"
                                :value="old('latitude', $masjid->latitude ?? '3.139003')"
                            />

                            <x-forms.input-field
                                label="Longitude"
                                name="longitude"
                                placeholder="Akan dikemas kini dari peta"
                                required="true"
                                readonly="true"
                                :error="$errors->first('longitude')"
                                help="Koordinat longitude (dikemas kini secara automatik dari peta)"
                                :value="old('longitude', $masjid->longitude ?? '101.686855')"
                            />
                        </div>
                    </div>

                    <!-- Map Section -->
                    <x-map-selector
                        :latitude="$masjid->latitude ?? '3.139003'"
                        :longitude="$masjid->longitude ?? '101.686855'"
                        height="400px"
                    />

                    <!-- Contact Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Hubungan</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-forms.input-field
                                label="Telefon"
                                name="telefon"
                                type="tel"
                                placeholder="Contoh: 03-1234 5678"
                                :error="$errors->first('telefon')"
                                :value="old('telefon', $masjid->telefon)"
                            />
                            
                            <x-forms.input-field
                                label="Faks"
                                name="faks"
                                type="tel"
                                placeholder="Contoh: 03-1234 5679"
                                :error="$errors->first('faks')"
                                :value="old('faks', $masjid->faks)"
                            />
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-forms.input-field
                                label="Email"
                                name="email"
                                type="email"
                                placeholder="Contoh: info@masjidalhidayah.my"
                                :error="$errors->first('email')"
                                :value="old('email', $masjid->email)"
                            />
                            
                            <x-forms.input-field
                                label="Laman Web"
                                name="laman_web"
                                type="url"
                                placeholder="Contoh: https://masjidalhidayah.my"
                                :error="$errors->first('laman_web')"
                                :value="old('laman_web', $masjid->laman_web)"
                            />
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Tambahan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-forms.date-picker
                                label="Tarikh Ditubuhkan"
                                name="tarikh_ditubuhkan"
                                placeholder="Pilih tarikh ditubuhkan"
                                :error="$errors->first('tarikh_ditubuhkan')"
                                help="Tarikh masjid/surau/musolla ditubuhkan"
                                :value="old('tarikh_ditubuhkan', $masjid->tarikh_ditubuhkan)"
                                maxDate="{{ date('Y-m-d') }}"
                            />

                            <x-forms.input-field
                                label="Kapasiti Jemaah"
                                name="kapasiti_jemaah"
                                type="number"
                                placeholder="Contoh: 500"
                                :error="$errors->first('kapasiti_jemaah')"
                                help="Jumlah jemaah yang boleh ditampung"
                                :value="old('kapasiti_jemaah', $masjid->kapasiti_jemaah)"
                            />
                        </div>
                    </div>

                    <!-- Registrar Information -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Maklumat Pendaftar</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-forms.input-field
                                label="Nama Pendaftar"
                                name="pendaftar_nama"
                                placeholder="Contoh: Ahmad bin Ali"
                                required="true"
                                :error="$errors->first('pendaftar_nama')"
                                :value="old('pendaftar_nama', $masjid->pendaftar_nama)"
                            />

                            <x-forms.input-field
                                label="Jawatan"
                                name="pendaftar_jawatan"
                                placeholder="Contoh: Imam / Pengerusi"
                                :error="$errors->first('pendaftar_jawatan')"
                                :value="old('pendaftar_jawatan', $masjid->pendaftar_jawatan)"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-forms.input-field
                                label="Telefon Pendaftar"
                                name="pendaftar_telefon"
                                type="tel"
                                placeholder="Contoh: 012-345 6789"
                                :error="$errors->first('pendaftar_telefon')"
                                :value="old('pendaftar_telefon', $masjid->pendaftar_telefon)"
                            />

                            <x-forms.input-field
                                label="Email Pendaftar"
                                name="pendaftar_email"
                                type="email"
                                placeholder="Contoh: ahmad@email.com"
                                :error="$errors->first('pendaftar_email')"
                                :value="old('pendaftar_email', $masjid->pendaftar_email)"
                            />
                        </div>
                    </div>

                    <!-- Existing Attachments -->
                    @if($masjid->attachments && $masjid->attachments->count() > 0)
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Lampiran Sedia Ada</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($masjid->attachments as $attachment)
                            <div class="bg-white border border-gray-200 rounded-sm p-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            @if($attachment->is_image)
                                                <span class="material-icons text-blue-500 text-sm">image</span>
                                            @else
                                                <span class="material-icons text-red-500 text-sm">picture_as_pdf</span>
                                            @endif
                                            <span class="text-xs font-medium text-gray-900 truncate">
                                                {{ $attachment->original_name }}
                                            </span>
                                        </div>
                                        <p class="text-2xs text-gray-500 mt-1">
                                            {{ $attachment->formatted_file_size }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-1 ml-2">
                                        <a href="{{ $attachment->file_url }}"
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800"
                                           title="Lihat">
                                            <span class="material-icons" style="font-size: 16px !important;">visibility</span>
                                        </a>
                                        <button type="button"
                                                onclick="removeAttachment({{ $attachment->id }})"
                                                class="text-red-600 hover:text-red-800"
                                                title="Padam">
                                            <span class="material-icons" style="font-size: 16px !important;">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- New Attachment Section -->
                    <div class="bg-gray-50 rounded-sm p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Lampiran Baharu</h3>

                        <x-forms.multiple-file-upload
                            label="Dokumen Lampiran"
                            name="attachments"
                            accept=".pdf,.png,.jpeg,.jpg"
                            :error="$errors->first('attachments')"
                            help="Muat naik dokumen sokongan baharu. Maksimum 5 fail."
                            maxSize="5MB"
                            allowedTypes="PDF, PNG, JPEG, JPG"
                            :maxFiles="5"
                        />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('senarai-masjid.index') }}"
                           class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-100 text-gray-700 btn-text rounded hover:bg-gray-200">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white btn-text rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                            Kemaskini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Remove Attachment Script -->
    <script>
        function removeAttachment(attachmentId) {
            if (confirm('Adakah anda pasti mahu memadamkan lampiran ini?')) {
                // Create a form to delete the attachment
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/senarai-masjid/attachment/${attachmentId}`;

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method override
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
