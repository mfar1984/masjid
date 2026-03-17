<div class="space-y-6">
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Tetapan Workflow Permohonan</h3>
        <p class="text-xs text-gray-600 mb-4">Konfigurasi proses kelulusan dan lawatan rumah.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Auto-Approve Jika Jumlah Di Bawah (RM)</label>
                <input type="number" step="0.01" name="auto_approve_below_amount" value="{{ $settings['auto_approve_below_amount'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                <p class="text-xs text-gray-500 mt-1">Permohonan di bawah jumlah ini akan diluluskan secara automatik</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Lawatan Rumah Wajib Jika Melebihi (RM)</label>
                <input type="number" step="0.01" name="home_visit_mandatory_above" value="{{ $settings['home_visit_mandatory_above'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="0.00">
                <p class="text-xs text-gray-500 mt-1">Permohonan melebihi jumlah ini wajib lawatan rumah</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Perlukan Lawatan Rumah</label>
                <select name="require_home_visit" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['require_home_visit'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['require_home_visit'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Tahap Kelulusan</label>
                <select name="approval_levels" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="1" {{ ($settings['approval_levels'] ?? '') == '1' ? 'selected' : '' }}>1 Tahap</option>
                    <option value="2" {{ ($settings['approval_levels'] ?? '') == '2' ? 'selected' : '' }}>2 Tahap</option>
                    <option value="3" {{ ($settings['approval_levels'] ?? '') == '3' ? 'selected' : '' }}>3 Tahap</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Email Notifikasi</label>
                <input type="email" name="notification_email" value="{{ $settings['notification_email'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded text-xs" placeholder="email@example.com">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Notifikasi SMS</label>
                <select name="notification_sms" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['notification_sms'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['notification_sms'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>
        </div>
    </div>
</div>
