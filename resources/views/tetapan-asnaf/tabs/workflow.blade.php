<div id="content-workflow" class="tab-content">
    <form method="POST" action="{{ route('tetapan-asnaf.update') }}">
        @csrf
        <input type="hidden" name="category" value="workflow">
        <div class="bg-gray-50 rounded p-4 border border-gray-200 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Workflow Settings</h3>
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="require_mesyuarat_attachment" value="1" {{ old('require_mesyuarat_attachment', $workflow['require_mesyuarat_attachment'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Wajib Attachment Mesyuarat untuk Approval</label>
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Approval Levels *</label>
                    <select name="approval_levels" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                        <option value="1" {{ old('approval_levels', $workflow['approval_levels'] ?? 1) == 1 ? 'selected' : '' }}>1 Level</option>
                        <option value="2" {{ old('approval_levels', $workflow['approval_levels'] ?? 1) == 2 ? 'selected' : '' }}>2 Levels</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="require_document_upload" value="1" {{ old('require_document_upload', $workflow['require_document_upload'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Require Document Upload</label>
                </div>
                <div>
                    <label class="form-label text-gray-700 mb-1">Minimum Documents Required *</label>
                    <input type="number" name="minimum_documents_required" value="{{ old('minimum_documents_required', $workflow['minimum_documents_required'] ?? 1) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="notification_enabled" value="1" {{ old('notification_enabled', $workflow['notification_enabled'] ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Enable Notifications</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="email_notification" value="1" {{ old('email_notification', $workflow['email_notification'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Email Notification</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="sms_notification" value="1" {{ old('sms_notification', $workflow['sms_notification'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">SMS Notification</label>
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
