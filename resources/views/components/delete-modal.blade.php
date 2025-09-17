@props([
    'id' => 'delete',
    'title' => 'Padam Rekod',
    'message' => 'Adakah anda pasti mahu memadamkan rekod ini?',
    'recordNameId' => 'deleteRecordName',
    'securityCodeId' => 'securityCode',
    'confirmCodeId' => 'confirmCode',
    'formId' => 'deleteForm',
    'confirmBtnId' => 'confirmDeleteBtn'
])

<!-- Delete Confirmation Modal -->
<div id="{{ $id }}Modal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <span class="material-icons text-red-600 text-xl">warning</span>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-medium text-gray-900 mt-4">{{ $title }}</h3>

            <!-- Message -->
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">{!! $message !!} <strong id="{{ $recordNameId }}"></strong>?</p>
                <p class="text-xs text-red-600 mt-2">
                    Tindakan ini tidak boleh dibatalkan. Sila taip kod keselamatan di bawah untuk mengesahkan.
                </p>
            </div>

            <!-- Security Code Section -->
            <div class="mt-4 px-7">
                <div class="bg-gray-100 p-3 rounded-md mb-3">
                    <span class="text-sm font-mono text-gray-700">Kod Keselamatan: </span>
                    <span id="{{ $securityCodeId }}" class="text-sm font-mono font-bold text-red-600"></span>
                </div>
                <input type="text"
                       id="{{ $confirmCodeId }}"
                       placeholder="Taip kod keselamatan di atas"
                       class="w-full px-3 py-2 border border-red-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-gray-900 placeholder-gray-400"
                       maxlength="6"
                       autocomplete="off"
                       inputmode="text">
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-center gap-3 mt-4">
                <button type="button"
                        onclick="hide{{ ucfirst($id) }}Modal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <form id="{{ $formId }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            id="{{ $confirmBtnId }}"
                            disabled
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        Padam Rekod
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
