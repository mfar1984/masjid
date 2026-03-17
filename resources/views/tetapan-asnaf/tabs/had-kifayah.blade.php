<div id="content-had-kifayah" class="tab-content">
    <div class="bg-blue-50 rounded-lg p-4">
        <h2 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
            <span class="material-icons mr-2 text-blue-600" style="font-size: 16px !important;">account_balance_wallet</span>
            Had Kifayah (Poverty Line)
        </h2>

        <form method="POST" action="{{ route('tetapan-asnaf.update') }}">
            @csrf
            <input type="hidden" name="category" value="had_kifayah">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="had_kifayah_individu" class="block text-xs font-medium text-gray-700 mb-2">Had Kifayah Individu (RM)</label>
                    <input type="number" step="0.01" id="had_kifayah_individu" name="had_kifayah_individu" 
                        value="{{ old('had_kifayah_individu', $hadKifayah['had_kifayah_individu'] ?? 1200) }}" 
                        required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="had_kifayah_pasangan" class="block text-xs font-medium text-gray-700 mb-2">Had Kifayah Pasangan (RM)</label>
                    <input type="number" step="0.01" id="had_kifayah_pasangan" name="had_kifayah_pasangan" 
                        value="{{ old('had_kifayah_pasangan', $hadKifayah['had_kifayah_pasangan'] ?? 1800) }}" 
                        required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="had_kifayah_anak" class="block text-xs font-medium text-gray-700 mb-2">Had Kifayah Per Anak (RM)</label>
                    <input type="number" step="0.01" id="had_kifayah_anak" name="had_kifayah_anak" 
                        value="{{ old('had_kifayah_anak', $hadKifayah['had_kifayah_anak'] ?? 400) }}" 
                        required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="had_kifayah_tanggungan" class="block text-xs font-medium text-gray-700 mb-2">Had Kifayah Per Tanggungan (RM)</label>
                    <input type="number" step="0.01" id="had_kifayah_tanggungan" name="had_kifayah_tanggungan" 
                        value="{{ old('had_kifayah_tanggungan', $hadKifayah['had_kifayah_tanggungan'] ?? 300) }}" 
                        required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="had_kifayah_max_anak" class="block text-xs font-medium text-gray-700 mb-2">Maksimum Bilangan Anak</label>
                    <input type="number" id="had_kifayah_max_anak" name="had_kifayah_max_anak" 
                        value="{{ old('had_kifayah_max_anak', $hadKifayah['had_kifayah_max_anak'] ?? 8) }}" 
                        required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="had_kifayah_max_tanggungan" class="block text-xs font-medium text-gray-700 mb-2">Maksimum Bilangan Tanggungan</label>
                    <input type="number" id="had_kifayah_max_tanggungan" name="had_kifayah_max_tanggungan" 
                        value="{{ old('had_kifayah_max_tanggungan', $hadKifayah['had_kifayah_max_tanggungan'] ?? 4) }}" 
                        required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="had_kifayah_auto_calculate" value="1" 
                            {{ old('had_kifayah_auto_calculate', $hadKifayah['had_kifayah_auto_calculate'] ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-xs text-gray-700">Auto calculate had kifayah berdasarkan saiz keluarga</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <span class="material-icons mr-2" style="font-size: 16px !important;">save</span>
                    Simpan Tetapan
                </button>
            </div>
        </form>
    </div>
</div>
