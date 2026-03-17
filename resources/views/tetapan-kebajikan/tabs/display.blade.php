<div class="space-y-6">
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Tetapan Paparan</h3>
        <p class="text-xs text-gray-600 mb-4">Konfigurasi paparan dan susunan data.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Papar Gambar Penerima</label>
                <select name="show_penerima_photo" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['show_penerima_photo'] ?? 'Ya') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['show_penerima_photo'] ?? 'Ya') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Papar Maklumat Kewangan</label>
                <select name="show_financial_details" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['show_financial_details'] ?? 'Ya') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['show_financial_details'] ?? 'Ya') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Item Per Halaman</label>
                <select name="items_per_page" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="10" {{ ($settings['items_per_page'] ?? '10') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ ($settings['items_per_page'] ?? '10') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ ($settings['items_per_page'] ?? '10') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ ($settings['items_per_page'] ?? '10') == '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Susunan Lalai</label>
                <select name="default_sort_order" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Terbaru" {{ ($settings['default_sort_order'] ?? 'Terbaru') == 'Terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="Terlama" {{ ($settings['default_sort_order'] ?? 'Terbaru') == 'Terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="Nama A-Z" {{ ($settings['default_sort_order'] ?? 'Terbaru') == 'Nama A-Z' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="Nama Z-A" {{ ($settings['default_sort_order'] ?? 'Terbaru') == 'Nama Z-A' ? 'selected' : '' }}>Nama Z-A</option>
                </select>
            </div>
        </div>
    </div>
</div>
