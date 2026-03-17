<div class="space-y-6">
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Had Bantuan Mengikut Kategori</h3>
        <p class="text-xs text-gray-600 mb-4">Tetapkan had minimum dan maksimum bantuan untuk setiap kategori program.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pendidikan -->
            <div class="space-y-3">
                <h4 class="text-xs font-medium text-gray-700">Pendidikan</h4>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Minimum (RM)</label>
                    <input type="number" step="0.01" name="had_minimum_pendidikan" value="{{ $settings['had_minimum_pendidikan'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Maksimum (RM)</label>
                    <input type="number" step="0.01" name="had_maksimum_pendidikan" value="{{ $settings['had_maksimum_pendidikan'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
            </div>

            <!-- Kesihatan -->
            <div class="space-y-3">
                <h4 class="text-xs font-medium text-gray-700">Kesihatan</h4>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Minimum (RM)</label>
                    <input type="number" step="0.01" name="had_minimum_kesihatan" value="{{ $settings['had_minimum_kesihatan'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Maksimum (RM)</label>
                    <input type="number" step="0.01" name="had_maksimum_kesihatan" value="{{ $settings['had_maksimum_kesihatan'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
            </div>

            <!-- Kecemasan -->
            <div class="space-y-3">
                <h4 class="text-xs font-medium text-gray-700">Kecemasan</h4>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Minimum (RM)</label>
                    <input type="number" step="0.01" name="had_minimum_kecemasan" value="{{ $settings['had_minimum_kecemasan'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Maksimum (RM)</label>
                    <input type="number" step="0.01" name="had_maksimum_kecemasan" value="{{ $settings['had_maksimum_kecemasan'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
            </div>

            <!-- Kebajikan Am -->
            <div class="space-y-3">
                <h4 class="text-xs font-medium text-gray-700">Kebajikan Am</h4>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Minimum (RM)</label>
                    <input type="number" step="0.01" name="had_minimum_kebajikan_am" value="{{ $settings['had_minimum_kebajikan_am'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Had Maksimum (RM)</label>
                    <input type="number" step="0.01" name="had_maksimum_kebajikan_am" value="{{ $settings['had_maksimum_kebajikan_am'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                </div>
            </div>
        </div>
    </div>
</div>
