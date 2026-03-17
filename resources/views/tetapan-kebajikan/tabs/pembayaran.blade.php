<div class="space-y-6">
    <div class="bg-gray-50 rounded p-4 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Tetapan Pembayaran</h3>
        <p class="text-xs text-gray-600 mb-4">Konfigurasi kaedah dan keperluan pembayaran bantuan.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Kaedah Bayaran Lalai</label>
                <select name="default_payment_method" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Tunai" {{ ($settings['default_payment_method'] ?? '') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="Cek" {{ ($settings['default_payment_method'] ?? '') == 'Cek' ? 'selected' : '' }}>Cek</option>
                    <option value="Bank Transfer" {{ ($settings['default_payment_method'] ?? '') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Barangan" {{ ($settings['default_payment_method'] ?? '') == 'Barangan' ? 'selected' : '' }}>Barangan</option>
                    <option value="Baucar" {{ ($settings['default_payment_method'] ?? '') == 'Baucar' ? 'selected' : '' }}>Baucar</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Aktifkan Tandatangan Digital</label>
                <select name="enable_digital_signature" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['enable_digital_signature'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['enable_digital_signature'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Perlukan Surat Akuan</label>
                <select name="require_acknowledgment_letter" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['require_acknowledgment_letter'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['require_acknowledgment_letter'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Perlukan Kelulusan Pembayaran</label>
                <select name="payment_approval_required" class="w-full px-3 py-2 border border-gray-300 rounded text-xs">
                    <option value="Ya" {{ ($settings['payment_approval_required'] ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ ($settings['payment_approval_required'] ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>
        </div>
    </div>
</div>
