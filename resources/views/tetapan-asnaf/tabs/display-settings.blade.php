<div id="content-display" class="tab-content">
    <form method="POST" action="{{ route('tetapan-asnaf.update') }}">
        @csrf
        <input type="hidden" name="category" value="display_settings">
        <div class="bg-gray-50 rounded p-4 border border-gray-200 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Display Settings (Public Website)</h3>
            <p class="text-xs text-gray-600 mb-4">Settings untuk paparan di website awam (future feature).</p>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="show_on_public_website" value="1" {{ old('show_on_public_website', $displaySettings['show_on_public_website'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Show on Public Website</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="accept_online_donations" value="1" {{ old('accept_online_donations', $displaySettings['accept_online_donations'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label class="ml-2 text-xs text-gray-700">Accept Online Donations</label>
                </div>
                
                <div>
                    <label class="form-label text-gray-700 mb-1">Donation Page Title</label>
                    <input type="text" name="donation_page_title" value="{{ old('donation_page_title', $displaySettings['donation_page_title'] ?? 'Sumbangan Zakat') }}" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="form-label text-gray-700 mb-1">Donation Page Description</label>
                    <textarea name="donation_page_description" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">{{ old('donation_page_description', $displaySettings['donation_page_description'] ?? 'Sumbangkan zakat anda untuk membantu golongan asnaf.') }}</textarea>
                </div>
                
                <div>
                    <label class="form-label text-gray-700 mb-1">Minimum Donation Amount (RM) *</label>
                    <input type="number" step="0.01" name="minimum_donation_amount" value="{{ old('minimum_donation_amount', $displaySettings['minimum_donation_amount'] ?? 10) }}" required class="w-full px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500">
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
