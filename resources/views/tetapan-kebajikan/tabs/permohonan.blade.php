<div class="space-y-6">
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Tetapan Permohonan</h3>
        <p class="text-xs text-gray-600 mb-4">Konfigurasi had dan keperluan permohonan bantuan.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Benarkan Permohonan Berganda</label>
                <select name="allow_multiple_applications" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['allow_multiple_applications'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['allow_multiple_applications'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Benarkan penerima membuat lebih dari 1 permohonan aktif</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tempoh Cooldown (Hari)</label>
                <input type="number" name="application_cooldown_days" value="{{ $settings['application_cooldown_days'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0">
                <p class="text-xs text-gray-500 mt-1">Bilangan hari sebelum boleh mohon semula</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Had Permohonan Setahun</label>
                <input type="number" name="max_applications_per_year" value="{{ $settings['max_applications_per_year'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0">
                <p class="text-xs text-gray-500 mt-1">Maksimum permohonan per penerima dalam setahun</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Perlukan Dokumen Sokongan</label>
                <select name="require_documents" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['require_documents'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['require_documents'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>
        </div>
    </div>
</div>
