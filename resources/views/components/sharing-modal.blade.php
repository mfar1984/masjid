<!-- Google Drive Style Sharing Modal -->
<div id="sharingModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="relative mx-auto bg-white" style="width: 480px !important; border-radius: 8px !important; box-shadow: 0 8px 10px 1px rgba(0,0,0,.14), 0 3px 14px 2px rgba(0,0,0,.12), 0 5px 5px -3px rgba(0,0,0,.2) !important; margin: 2rem !important;">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between" style="padding: 24px 24px 0 24px !important;">
            <h3 style="font-family: 'Google Sans', 'Roboto', sans-serif !important; font-size: 22px !important; font-weight: 400 !important; color: #3c4043 !important; margin: 0 !important; line-height: 28px !important;">
                Kongsi "<span id="sharingItemName"></span>"
            </h3>
            <div class="flex items-center" style="gap: 4px !important;">
                <button class="hover:bg-gray-100 rounded-full transition-colors" style="padding: 8px !important; color: #5f6368 !important; width: 36px !important; height: 36px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-icons" style="font-size: 20px !important;">help_outline</span>
                </button>
                <button onclick="openSharingSettings()" class="hover:bg-gray-100 rounded-full transition-colors" style="padding: 8px !important; color: #5f6368 !important; width: 36px !important; height: 36px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-icons" style="font-size: 20px !important;">settings</span>
                </button>
                <button onclick="closeSharingModal()" class="hover:bg-gray-100 rounded-full transition-colors" style="padding: 8px !important; color: #5f6368 !important; width: 36px !important; height: 36px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                    <span class="material-icons" style="font-size: 20px !important;">close</span>
                </button>
            </div>
        </div>

        <!-- Add People Input -->
        <div style="padding: 24px 24px 16px 24px !important;">
            <div class="relative">
                <input type="text" id="kodMasjidInput" placeholder="Masukkan Kod Masjid untuk Kongsi Item"
                       class="w-full border rounded focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all"
                       style="padding: 11px 12px !important; font-family: 'Roboto', sans-serif !important; font-size: 14px !important; border-color: #dadce0 !important; background-color: #fff !important;" maxlength="6">
                <button onclick="addMasjidShare()" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                        style="padding: 4px 8px !important; font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important;">
                    Kongsi
                </button>
            </div>
        </div>

        <!-- People with Access Section -->
        <div style="padding: 0 24px 8px 24px !important;">
            <h4 style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important; color: #3c4043 !important; margin: 0 0 16px 0 !important;">
                Kod yang boleh akses
            </h4>
            
            <!-- Current User -->
            <div class="flex items-center justify-between" style="padding: 8px 0 !important;">
                <div class="flex items-center" style="gap: 12px !important;">
                    <div class="rounded-full" style="width: 32px !important; height: 32px !important; background-color: #fbbc04 !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                        <span style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important; color: white !important;">{{ auth()->user()->initials }}</span>
                    </div>
                    <div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; margin: 0 !important;">{{ auth()->user()->name }} (you)</div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 !important;">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; text-align: left !important;">
                    {{ auth()->user()->role->name ?? 'Owner' }}
                </div>
            </div>
        </div>

        <!-- Shared Masjids List -->
        <div id="sharedMasjidsList" style="padding: 0 24px !important;">
            <!-- Dynamic content will be inserted here -->
        </div>

        <!-- General Access Section -->
        <div style="padding: 8px 24px 16px 24px !important;">
            <h4 style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important; color: #3c4043 !important; margin: 0 0 16px 0 !important;">
                Akses am
            </h4>
            
            <div class="flex items-center justify-between" style="padding: 8px 0 !important;">
                <div class="flex items-center" style="gap: 12px !important;">
                    <div class="rounded-full flex items-center justify-center" style="width: 32px !important; height: 32px !important; background-color: #34a853 !important;">
                        <span class="material-icons" style="font-size: 16px !important; color: white !important;">link</span>
                    </div>
                    <div>
                        <button onclick="toggleAccessDropdown()" class="flex items-center hover:bg-gray-100 rounded transition-colors" style="padding: 4px 8px !important; gap: 4px !important;">
                            <span style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important;">Sesiapa dengan pautan</span>
                            <span class="material-icons" style="font-size: 16px !important; color: #5f6368 !important;">expand_more</span>
                        </button>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 0 0 12px !important;">Sesiapa di internet dengan pautan boleh lihat</div>
                    </div>
                </div>
                <div id="viewerDropdownContainer" class="relative">
                    <button onclick="toggleViewerDropdown()" class="flex items-center hover:bg-gray-100 rounded transition-colors" style="padding: 4px 8px !important; gap: 4px !important;">
                        <span id="viewerDropdownText" style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important;">Penonton</span>
                        <span class="material-icons" style="font-size: 16px !important; color: #5f6368 !important;">expand_more</span>
                    </button>
                    
                    <!-- Viewer Role Dropdown -->
                    <div id="viewerDropdown" class="absolute right-0 top-full mt-1 bg-white rounded shadow-lg border hidden" style="width: 200px !important; z-index: 9999 !important; border-color: #dadce0 !important; box-shadow: 0 2px 10px rgba(0,0,0,0.2) !important;">
                        <div style="padding: 8px 0 !important;">
                            <div style="padding: 8px 16px !important; font-family: 'Roboto', sans-serif !important; font-size: 11px !important; font-weight: 500 !important; color: #5f6368 !important; text-transform: uppercase !important; letter-spacing: 0.8px !important;">
                                Peranan
                            </div>
                            <button onclick="setViewerRole('viewer')" class="w-full flex items-center text-left hover:bg-gray-50 transition-colors" style="padding: 8px 16px !important; gap: 12px !important;">
                                <span class="material-icons" style="font-size: 16px !important; color: #1a73e8 !important;">check</span>
                                <span style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important;">Penonton</span>
                            </button>
                            <button onclick="setViewerRole('commenter')" class="w-full flex items-center text-left hover:bg-gray-50 transition-colors" style="padding: 8px 16px !important; gap: 12px !important;">
                                <span class="material-icons" style="font-size: 16px !important; color: transparent !important;">check</span>
                                <span style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important;">Pengulas</span>
                            </button>
                            <button onclick="setViewerRole('editor')" class="w-full flex items-center text-left hover:bg-gray-50 transition-colors" style="padding: 8px 16px !important; gap: 12px !important;">
                                <span class="material-icons" style="font-size: 16px !important; color: transparent !important;">check</span>
                                <span style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important;">Editor</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Access Level Dropdown (hidden by default) -->
            <div id="accessDropdown" class="absolute bg-white rounded shadow-lg border hidden" style="width: 280px !important; z-index: 9999 !important; border-color: #dadce0 !important; margin-top: 8px !important; box-shadow: 0 2px 10px rgba(0,0,0,0.2) !important;">
                <div style="padding: 8px 0 !important;">
                                    <div style="padding: 8px 16px !important; font-family: 'Roboto', sans-serif !important; font-size: 11px !important; font-weight: 500 !important; color: #5f6368 !important; text-transform: uppercase !important; letter-spacing: 0.8px !important;">
                                        Akses am
                                    </div>
                                    <button onclick="setAccessLevel('restricted')" class="w-full flex items-center text-left hover:bg-gray-50 transition-colors" style="padding: 12px 16px !important; gap: 12px !important;">
                                        <span class="material-icons" style="font-size: 16px !important; color: #5f6368 !important;">lock</span>
                                        <div>
                                            <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; margin: 0 0 2px 0 !important;">Terhad</div>
                                            <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 !important;">Hanya orang yang ditambah boleh akses</div>
                                        </div>
                                    </button>
                                    <button onclick="setAccessLevel('anyone_with_link')" class="w-full flex items-center text-left hover:bg-gray-50 transition-colors" style="padding: 12px 16px !important; gap: 12px !important;">
                                        <span class="material-icons" style="font-size: 16px !important; color: #5f6368 !important;">link</span>
                                        <div>
                                            <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; margin: 0 0 2px 0 !important;">Sesiapa dengan pautan</div>
                                            <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 !important;">Sesiapa di internet dengan pautan boleh lihat</div>
                                        </div>
                                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between" style="padding: 16px 24px 24px 24px !important;">
            <button onclick="copyShareLink()" class="flex items-center text-blue-600 hover:bg-blue-50 rounded transition-colors" style="padding: 8px 16px !important; font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important; gap: 8px !important; border: 1px solid #dadce0 !important; background-color: white !important;">
                <span class="material-icons" style="font-size: 16px !important;">link</span>
                Salin pautan
            </button>
            <button onclick="closeSharingModal()" class="bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition-colors"
                    style="padding: 10px 24px !important; font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important;">
                Selesai
            </button>
        </div>
    </div>
</div>

<!-- Advanced Sharing Settings Modal -->
<div id="sharingSettingsModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="relative mx-auto bg-white" style="width: 480px !important; border-radius: 8px !important; box-shadow: 0 8px 10px 1px rgba(0,0,0,.14), 0 3px 14px 2px rgba(0,0,0,.12), 0 5px 5px -3px rgba(0,0,0,.2) !important; margin: 2rem !important;">
        
        <!-- Settings Header -->
        <div class="flex items-center justify-between" style="padding: 24px 24px 0 24px !important;">
            <h3 style="font-family: 'Google Sans', 'Roboto', sans-serif !important; font-size: 22px !important; font-weight: 400 !important; color: #3c4043 !important; margin: 0 !important;">
                Tetapan perkongsian
            </h3>
            <button onclick="closeSharingSettings()" class="hover:bg-gray-100 rounded-full transition-colors" style="padding: 8px !important; color: #5f6368 !important; width: 36px !important; height: 36px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                <span class="material-icons" style="font-size: 20px !important;">close</span>
            </button>
        </div>

        <!-- Settings Body -->
        <div style="padding: 24px 24px 16px 24px !important;">
            <h4 style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important; color: #3c4043 !important; margin: 0 0 16px 0 !important;">
                Kebenaran lalai
            </h4>
            <div style="display: flex !important; flex-direction: column !important; gap: 8px !important;">
                <label class="flex items-center hover:bg-gray-50 rounded transition-colors cursor-pointer" style="padding: 8px !important; gap: 12px !important;">
                    <input type="radio" name="defaultPermission" value="view" checked style="margin: 0 !important;">
                    <div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; margin: 0 0 2px 0 !important;">Penonton</div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 !important;">Boleh lihat sahaja</div>
                    </div>
                </label>
                <label class="flex items-center hover:bg-gray-50 rounded transition-colors cursor-pointer" style="padding: 8px !important; gap: 12px !important;">
                    <input type="radio" name="defaultPermission" value="comment" style="margin: 0 !important;">
                    <div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; margin: 0 0 2px 0 !important;">Pengulas</div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 !important;">Boleh lihat dan beri ulasan</div>
                    </div>
                </label>
                <label class="flex items-center hover:bg-gray-50 rounded transition-colors cursor-pointer" style="padding: 8px !important; gap: 12px !important;">
                    <input type="radio" name="defaultPermission" value="edit" style="margin: 0 !important;">
                    <div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; margin: 0 0 2px 0 !important;">Editor</div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 !important;">Boleh atur, tambah dan edit fail</div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Settings Footer -->
        <div class="flex items-center justify-end" style="padding: 16px 24px 24px 24px !important; gap: 8px !important;">
            <button onclick="closeSharingSettings()" class="text-blue-600 font-medium rounded hover:bg-blue-50 transition-colors"
                    style="padding: 10px 24px !important; font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important;">
                Batal
            </button>
            <button onclick="saveSharingSettings()" class="bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition-colors"
                    style="padding: 10px 24px !important; font-family: 'Roboto', sans-serif !important; font-size: 14px !important; font-weight: 500 !important;">
                Simpan
            </button>
        </div>
    </div>
</div>

