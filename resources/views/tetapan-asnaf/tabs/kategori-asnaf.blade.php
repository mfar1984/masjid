<div id="content-kategori" class="tab-content">
    <form method="POST" action="{{ route('tetapan-asnaf.update') }}">
        @csrf
        <input type="hidden" name="category" value="kategori_asnaf">
        <div class="bg-gray-50 rounded p-4 border border-gray-200 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Kategori Asnaf Allocation (%)</h3>
            <p class="text-xs text-gray-600 mb-4">Tetapkan peratusan agihan untuk setiap kategori asnaf. Total tidak perlu 100% (flexible).</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-gray-700 mb-1">Fakir (%) *</label>
                    <input type="number" step="0.01" name="fakir_percentage" value="{{ old('fakir_percentage', $kategoriAsnaf['fakir_percentage'] ?? 20) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Miskin (%) *</label>
                    <input type="number" step="0.01" name="miskin_percentage" value="{{ old('miskin_percentage', $kategoriAsnaf['miskin_percentage'] ?? 20) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Amil (%) *</label>
                    <input type="number" step="0.01" name="amil_percentage" value="{{ old('amil_percentage', $kategoriAsnaf['amil_percentage'] ?? 12.5) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Muallaf (%) *</label>
                    <input type="number" step="0.01" name="muallaf_percentage" value="{{ old('muallaf_percentage', $kategoriAsnaf['muallaf_percentage'] ?? 12.5) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Riqab (%) *</label>
                    <input type="number" step="0.01" name="riqab_percentage" value="{{ old('riqab_percentage', $kategoriAsnaf['riqab_percentage'] ?? 12.5) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Gharimin (%) *</label>
                    <input type="number" step="0.01" name="gharimin_percentage" value="{{ old('gharimin_percentage', $kategoriAsnaf['gharimin_percentage'] ?? 12.5) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Fisabilillah (%) *</label>
                    <input type="number" step="0.01" name="fisabilillah_percentage" value="{{ old('fisabilillah_percentage', $kategoriAsnaf['fisabilillah_percentage'] ?? 5) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Ibnu Sabil (%) *</label>
                    <input type="number" step="0.01" name="ibnu_sabil_percentage" value="{{ old('ibnu_sabil_percentage', $kategoriAsnaf['ibnu_sabil_percentage'] ?? 5) }}" required min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
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
