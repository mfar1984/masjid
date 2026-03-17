<div class="space-y-6">
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Kategori Penerima</h3>
        <p class="text-xs text-gray-600 mb-4">Aktifkan atau nyahaktifkan kategori penerima bantuan.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">OKU (Orang Kurang Upaya)</label>
                <select name="enable_oku" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['enable_oku'] ?? 'Ya') == 'Ya' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak" {{ ($settings['enable_oku'] ?? 'Ya') == 'Tidak' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Anak Yatim</label>
                <select name="enable_yatim" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['enable_yatim'] ?? 'Ya') == 'Ya' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak" {{ ($settings['enable_yatim'] ?? 'Ya') == 'Tidak' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Ibu Tunggal</label>
                <select name="enable_ibu_tunggal" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['enable_ibu_tunggal'] ?? 'Ya') == 'Ya' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak" {{ ($settings['enable_ibu_tunggal'] ?? 'Ya') == 'Tidak' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Warga Emas</label>
                <select name="enable_warga_emas" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['enable_warga_emas'] ?? 'Ya') == 'Ya' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak" {{ ($settings['enable_warga_emas'] ?? 'Ya') == 'Tidak' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
</div>
