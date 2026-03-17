<div id="content-permohonan" class="tab-content">
    <form method="POST" action="{{ route('tetapan-asnaf.update') }}">
        @csrf
        <input type="hidden" name="category" value="permohonan">
        <div class="bg-gray-50 rounded p-4 border border-gray-200 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Permohonan Settings</h3>
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="allow_multiple_applications" value="1" {{ old('allow_multiple_applications', $permohonan['allow_multiple_applications'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Allow Multiple Applications</label>
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Maximum Applications Per Year (0 = Unlimited) *</label>
                    <input type="number" name="maximum_applications_per_year" value="{{ old('maximum_applications_per_year', $permohonan['maximum_applications_per_year'] ?? 0) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Minimum Days Between Applications *</label>
                    <input type="number" name="minimum_days_between_applications" value="{{ old('minimum_days_between_applications', $permohonan['minimum_days_between_applications'] ?? 30) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="require_home_visit" value="1" {{ old('require_home_visit', $permohonan['require_home_visit'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Require Home Visit</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="allow_adhoc_agihan" value="1" {{ old('allow_adhoc_agihan', $permohonan['allow_adhoc_agihan'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Allow Ad-hoc/Emergency Agihan</label>
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
