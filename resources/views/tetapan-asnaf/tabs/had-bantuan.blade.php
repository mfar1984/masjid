<div id="content-had-bantuan" class="tab-content">
    <form method="POST" action="{{ route('tetapan-asnaf.update') }}">
        @csrf
        <input type="hidden" name="category" value="had_bantuan">
        <div class="bg-green-50 rounded p-4 border border-gray-200 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                <span class="material-icons mr-2 text-green-600" style="font-size: 16px !important;">volunteer_activism</span>
                Peratusan Agihan Mengikut Kategori Asnaf
            </h3>
            <p class="text-xs text-gray-600 mb-4">Tetapkan peratusan agihan zakat untuk setiap kategori asnaf. Total tidak semestinya 100%.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Fakir (%)</label>
                    <input type="number" step="0.01" name="fakir_percentage" value="{{ old('fakir_percentage', $hadBantuan['fakir_percentage'] ?? 25) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Miskin (%)</label>
                    <input type="number" step="0.01" name="miskin_percentage" value="{{ old('miskin_percentage', $hadBantuan['miskin_percentage'] ?? 25) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Amil (%)</label>
                    <input type="number" step="0.01" name="amil_percentage" value="{{ old('amil_percentage', $hadBantuan['amil_percentage'] ?? 12.5) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Muallaf (%)</label>
                    <input type="number" step="0.01" name="muallaf_percentage" value="{{ old('muallaf_percentage', $hadBantuan['muallaf_percentage'] ?? 12.5) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Riqab (%)</label>
                    <input type="number" step="0.01" name="riqab_percentage" value="{{ old('riqab_percentage', $hadBantuan['riqab_percentage'] ?? 5) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Gharimin (%)</label>
                    <input type="number" step="0.01" name="gharimin_percentage" value="{{ old('gharimin_percentage', $hadBantuan['gharimin_percentage'] ?? 10) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Fisabilillah (%)</label>
                    <input type="number" step="0.01" name="fisabilillah_percentage" value="{{ old('fisabilillah_percentage', $hadBantuan['fisabilillah_percentage'] ?? 5) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Ibnu Sabil (%)</label>
                    <input type="number" step="0.01" name="ibnu_sabil_percentage" value="{{ old('ibnu_sabil_percentage', $hadBantuan['ibnu_sabil_percentage'] ?? 5) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                Simpan Tetapan
            </button>
        </div>
    </form>
</div>
