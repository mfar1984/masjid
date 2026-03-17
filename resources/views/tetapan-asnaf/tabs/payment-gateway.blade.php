<div id="content-payment" class="tab-content">
    <form method="POST" action="{{ route('tetapan-asnaf.update') }}">
        @csrf
        <input type="hidden" name="category" value="payment_gateway">
        <div class="bg-gray-50 rounded p-4 border border-gray-200 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Payment Gateway Integration (Chip-Asia)</h3>
            <p class="text-xs text-gray-600 mb-4">Configure Chip-Asia payment gateway untuk online donations.</p>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="payment_gateway_enabled" value="1" {{ old('payment_gateway_enabled', $paymentGateway['payment_gateway_enabled'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Enable Payment Gateway</label>
                </div>
                
                <div class="border-t border-gray-300 pt-4">
                    <h4 class="text-xs font-semibold text-gray-900 mb-3">Chip-Asia Credentials</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="form-label text-gray-700 mb-1">Brand ID</label>
                            <input type="text" name="chip_asia_brand_id" value="{{ old('chip_asia_brand_id', $paymentGateway['chip_asia_brand_id'] ?? '') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="form-label text-gray-700 mb-1">API Key</label>
                            <input type="password" name="chip_asia_api_key" value="{{ old('chip_asia_api_key', $paymentGateway['chip_asia_api_key'] ?? '') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Will be encrypted in database</p>
                        </div>
                        <div>
                            <label class="form-label text-gray-700 mb-1">Secret Key</label>
                            <input type="password" name="chip_asia_secret_key" value="{{ old('chip_asia_secret_key', $paymentGateway['chip_asia_secret_key'] ?? '') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Will be encrypted in database</p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="chip_asia_test_mode" value="1" {{ old('chip_asia_test_mode', $paymentGateway['chip_asia_test_mode'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label class="ml-2 text-xs text-gray-700">Test Mode (Sandbox)</label>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-300 pt-4">
                    <h4 class="text-xs font-semibold text-gray-900 mb-3">Bank Account Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-gray-700 mb-1">Account Name</label>
                            <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $paymentGateway['bank_account_name'] ?? '') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="form-label text-gray-700 mb-1">Account Number</label>
                            <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $paymentGateway['bank_account_number'] ?? '') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="form-label text-gray-700 mb-1">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $paymentGateway['bank_name'] ?? '') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="form-label text-gray-700 mb-1">SWIFT Code</label>
                            <input type="text" name="bank_swift_code" value="{{ old('bank_swift_code', $paymentGateway['bank_swift_code'] ?? '') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
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
