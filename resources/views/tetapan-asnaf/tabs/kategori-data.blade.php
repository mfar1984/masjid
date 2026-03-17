<div id="content-kategori-data" class="tab-content hidden">
    <div class="space-y-6">
    <!-- Bangsa -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Bangsa</h3>
                <p class="text-xs text-gray-600">Urus kategori bangsa asnaf</p>
            </div>
            <button type="button" onclick="openAddModal('bangsa')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bangsa as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'bangsa', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'bangsa')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Agama -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Agama</h3>
                <p class="text-xs text-gray-600">Urus kategori agama asnaf</p>
            </div>
            <button type="button" onclick="openAddModal('agama')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($agama as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'agama', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'agama')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Status Perkahwinan -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Status Perkahwinan</h3>
                <p class="text-xs text-gray-600">Urus kategori status perkahwinan asnaf</p>
            </div>
            <button type="button" onclick="openAddModal('status_perkahwinan')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($statusPerkahwinan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'status_perkahwinan', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'status_perkahwinan')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Negeri -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Negeri</h3>
                <p class="text-xs text-gray-600">Urus kategori negeri asnaf</p>
            </div>
            <button type="button" onclick="openAddModal('negeri')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($negeri as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'negeri', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'negeri')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kategori Asnaf -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Kategori Asnaf</h3>
                <p class="text-xs text-gray-600">Urus kategori asnaf (8 golongan)</p>
            </div>
            <button type="button" onclick="openAddModal('kategori_asnaf')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoriAsnafList as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'kategori_asnaf', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'kategori_asnaf')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Status Pekerjaan -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Status Pekerjaan</h3>
                <p class="text-xs text-gray-600">Urus kategori status pekerjaan asnaf</p>
            </div>
            <button type="button" onclick="openAddModal('status_pekerjaan')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($statusPekerjaan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'status_pekerjaan', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'status_pekerjaan')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Status Kesihatan -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Status Kesihatan</h3>
                <p class="text-xs text-gray-600">Urus kategori status kesihatan asnaf</p>
            </div>
            <button type="button" onclick="openAddModal('status_kesihatan')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($statusKesihatan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'status_kesihatan', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'status_kesihatan')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kewarganegaraan -->
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Kewarganegaraan</h3>
                <p class="text-xs text-gray-600">Urus kategori kewarganegaraan asnaf</p>
            </div>
            <button type="button" onclick="openAddModal('kewarganegaraan')" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                Tambah
            </button>
        </div>

        <div class="overflow-x-auto bg-white rounded border border-gray-200">
            <table class="min-w-full text-xs">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Nama</th>
                        <th class="px-3 py-2 text-left text-gray-700 font-medium">Kod</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Urutan</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Status</th>
                        <th class="px-3 py-2 text-center text-gray-700 font-medium">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kewarganegaraan as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-900">{{ $item->nama_kategori }}</td>
                        <td class="px-3 py-2 text-gray-600">{{ $item->kod_kategori ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">{{ $item->urutan }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($item->status === 'Aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <button type="button" onclick="openEditModal({{ $item->id }}, 'kewarganegaraan', '{{ $item->nama_kategori }}', '{{ $item->kod_kategori }}', {{ $item->urutan }}, '{{ $item->status }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <span class="material-icons" style="font-size: 16px !important;">edit</span>
                            </button>
                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'kewarganegaraan')" class="text-red-600 hover:text-red-800">
                                <span class="material-icons" style="font-size: 16px !important;">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Tambah Kategori</h3>
            <form id="addForm" method="POST" action="{{ route('tetapan-asnaf.kategori.store') }}">
                @csrf
                <input type="hidden" name="jenis_kategori" id="add_jenis_kategori">
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Nama *</label>
                    <input type="text" name="nama_kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Kod</label>
                    <input type="text" name="kod_kategori" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Urutan</label>
                    <input type="number" name="urutan" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Edit Kategori</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenis_kategori" id="edit_jenis_kategori">
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Nama *</label>
                    <input type="text" name="nama_kategori" id="edit_nama" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Kod</label>
                    <input type="text" name="kod_kategori" id="edit_kod" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Urutan</label>
                    <input type="number" name="urutan" id="edit_urutan" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="edit_status" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Kemaskini</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <span class="material-icons text-red-600">warning</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 mb-2">Padam Kategori</h3>
            <p class="text-xs text-gray-500 mb-4">Adakah anda pasti ingin memadam kategori ini?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-2">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700">Padam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddModal(jenis) {
    document.getElementById('add_jenis_kategori').value = jenis;
    document.getElementById('addModal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addForm').reset();
}

function openEditModal(id, jenis, nama, kod, urutan, status) {
    document.getElementById('edit_jenis_kategori').value = jenis;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_kod').value = kod || '';
    document.getElementById('edit_urutan').value = urutan;
    document.getElementById('edit_status').value = status;
    document.getElementById('editForm').action = '{{ url("tetapan-asnaf/kategori") }}/' + id;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function confirmDelete(id, jenis) {
    document.getElementById('deleteForm').action = '{{ url("tetapan-asnaf/kategori") }}/' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>

    </div>
</div>
