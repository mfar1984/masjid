/**
 * Google Drive Style Sharing Modal System
 * Handles document and folder sharing with Kod Masjid system
 */

class SharingModal {
    constructor() {
        this.currentItem = null;
        this.currentAccessLevel = 'restricted';
        this.currentViewerRole = 'viewer';
        this.sharedMasjids = [];
        this.currentUser = null;
        this.init();
    }

    init() {
        // Close modal when clicking outside (like kariah delete modal)
        document.addEventListener('DOMContentLoaded', () => {
            const sharingModal = document.getElementById('sharingModal');
            const settingsModal = document.getElementById('sharingSettingsModal');

            if (sharingModal) {
                sharingModal.addEventListener('click', (e) => {
                    if (e.target === sharingModal) {
                        this.close();
                    }
                });
            }

            if (settingsModal) {
                settingsModal.addEventListener('click', (e) => {
                    if (e.target === settingsModal) {
                        this.closeSettings();
                    }
                });
            }
        });

        // Close modal with Escape key (like kariah delete modal)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.close();
                this.closeSettings();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#accessDropdown') && !e.target.closest('[onclick="toggleAccessDropdown()"]')) {
                this.hideAccessDropdown();
            }

            if (!e.target.closest('#viewerDropdown') && !e.target.closest('[onclick="toggleViewerDropdown()"]')) {
                this.hideViewerDropdown();
            }

            // Close permission dropdowns when clicking outside
            if (!e.target.closest('[id^="permissionDropdown_"]') && !e.target.closest('[onclick*="togglePermissionDropdown"]')) {
                this.hideAllPermissionDropdowns();
            }
        });

        // Handle Enter key in Kod Masjid input
        const kodMasjidInput = document.getElementById('kodMasjidInput');
        if (kodMasjidInput) {
            kodMasjidInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.addMasjidShare();
                }
            });

            // Format input to uppercase and limit to 6 characters
            kodMasjidInput.addEventListener('input', (e) => {
                let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                if (value.length > 6) value = value.substring(0, 6);
                e.target.value = value;
            });
        }
    }

    async open(type, id, name) {
        this.currentItem = { type, id, name };
        
        // Update modal title
        document.getElementById('sharingItemName').textContent = name;
        
        // Load sharing data (this will also initialize UI with correct access level)
        await this.loadSharingData();

        // Show modal
        document.getElementById('sharingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus on Kod Masjid input
        setTimeout(() => {
            document.getElementById('kodMasjidInput').focus();
        }, 100);
    }

    close() {
        document.getElementById('sharingModal').classList.add('hidden');
        document.body.style.overflow = '';
        this.hideAccessDropdown();
        this.currentItem = null;
    }

    async loadSharingData() {
        if (!this.currentItem) return;

        try {
            const response = await fetch(`/api/documents/sharing/${this.currentItem.type}/${this.currentItem.id}`, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('API Response Status:', response.status);
            if (response.ok) {
                const data = await response.json();
                console.log('API Response Data:', data);
                if (data.success) {
                    this.currentAccessLevel = data.data.access_level || 'restricted';
                    this.sharedMasjids = data.data.shared_masjids || [];
                    this.currentUser = data.data.current_user || null;
                    console.log('Parsed Data:', {
                        accessLevel: this.currentAccessLevel,
                        sharedMasjidsCount: this.sharedMasjids.length,
                        currentUser: this.currentUser
                    });
                    this.updateUI();
                } else {
                    console.error('API returned success=false:', data.message);
                }
            } else {
                console.error('API request failed:', response.status, response.statusText);
            }
        } catch (error) {
            console.error('Error loading sharing data:', error);
        }
    }

    updateUI() {
        // Update access level UI (text and dropdown visibility)
        this.updateAccessLevelUI(this.currentAccessLevel);

        // Update current user display if we have user data
        this.updateCurrentUserDisplay();

        // Update shared masjids list
        this.renderSharedMasjids();
    }

    updateCurrentUserDisplay() {
        if (!this.currentUser) return;

        // Update user avatar initials
        const avatarElement = document.querySelector('.sharing-modal .rounded-full span');
        if (avatarElement) {
            avatarElement.textContent = this.currentUser.initials;
        }

        // Update user name
        const nameElement = document.querySelector('.sharing-modal [style*="color: #3c4043"]');
        if (nameElement && nameElement.textContent.includes('(you)')) {
            nameElement.textContent = `${this.currentUser.name} (you)`;
        }

        // Update user email
        const emailElement = document.querySelector('.sharing-modal [style*="color: #5f6368"]:not([style*="Owner"])');
        if (emailElement && emailElement.textContent.includes('@')) {
            emailElement.textContent = this.currentUser.email;
        }

        // Update user role
        const roleElement = document.querySelector('.sharing-modal [style*="color: #5f6368"]:last-child');
        if (roleElement && !roleElement.textContent.includes('@')) {
            roleElement.textContent = this.currentUser.role;
        }
    }

    renderSharedMasjids() {
        const container = document.getElementById('sharedMasjidsList');
        if (!container) {
            return;
        }

        container.innerHTML = '';

        this.sharedMasjids.forEach(masjid => {
            const masjidElement = document.createElement('div');
            masjidElement.className = 'flex items-center justify-between';
            masjidElement.style.cssText = 'padding: 8px 0 !important;';
            masjidElement.innerHTML = `
                <div class="flex items-center" style="gap: 12px !important;">
                    <div class="rounded-full flex items-center justify-center" style="width: 32px !important; height: 32px !important; background-color: #34a853 !important;">
                        <span class="material-icons" style="font-size: 16px !important; color: white !important;">business</span>
                    </div>
                    <div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important; margin: 0 !important;">
                            ${masjid.nama}
                        </div>
                        <div style="font-family: 'Roboto', sans-serif !important; font-size: 12px !important; color: #5f6368 !important; margin: 0 !important;">
                            ${masjid.kod_masjid}
                        </div>
                    </div>
                </div>
                <div class="flex items-center" style="gap: 8px !important;">
                    <div class="relative">
                        <button onclick="sharingModal.togglePermissionDropdown('${masjid.kod_masjid}')"
                                class="flex items-center hover:bg-gray-100 rounded transition-colors"
                                style="padding: 4px 8px !important; gap: 4px !important; font-family: 'Roboto', sans-serif !important; font-size: 14px !important; color: #3c4043 !important;"
                                id="permissionBtn_${masjid.kod_masjid}">
                            <span>${this.getPermissionText(masjid.permission_level)}</span>
                            <span class="material-icons" style="font-size: 16px !important; color: #5f6368 !important;">expand_more</span>
                        </button>
                        <!-- Permission Dropdown -->
                        <div id="permissionDropdown_${masjid.kod_masjid}" class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 hidden" style="z-index: 9999 !important;">
                            <button onclick="sharingModal.updateMasjidPermission('${masjid.kod_masjid}', 'view')"
                                    class="w-full flex items-center px-4 py-2 text-left hover:bg-gray-50 transition-colors ${masjid.permission_level === 'view' ? 'bg-blue-50 text-blue-600' : 'text-gray-700'}">
                                <span class="material-icons text-sm mr-3 flex items-center justify-center">visibility</span>
                                <div class="flex flex-col justify-center">
                                    <div class="text-sm font-medium leading-tight" style="font-family: 'Poppins', sans-serif; font-size: 11px;">Lihat</div>
                                    <div class="text-xs text-gray-500 leading-tight" style="font-family: 'Poppins', sans-serif; font-size: 9px;">Boleh lihat sahaja</div>
                                </div>
                            </button>
                            <button onclick="sharingModal.updateMasjidPermission('${masjid.kod_masjid}', 'comment')"
                                    class="w-full flex items-center px-4 py-2 text-left hover:bg-gray-50 transition-colors ${masjid.permission_level === 'comment' ? 'bg-blue-50 text-blue-600' : 'text-gray-700'}">
                                <span class="material-icons text-sm mr-3 flex items-center justify-center">comment</span>
                                <div class="flex flex-col justify-center">
                                    <div class="text-sm font-medium leading-tight" style="font-family: 'Poppins', sans-serif; font-size: 11px;">Komen</div>
                                    <div class="text-xs text-gray-500 leading-tight" style="font-family: 'Poppins', sans-serif; font-size: 9px;">Boleh lihat dan komen</div>
                                </div>
                            </button>
                            <button onclick="sharingModal.updateMasjidPermission('${masjid.kod_masjid}', 'edit')"
                                    class="w-full flex items-center px-4 py-2 text-left hover:bg-gray-50 transition-colors ${masjid.permission_level === 'edit' ? 'bg-blue-50 text-blue-600' : 'text-gray-700'}">
                                <span class="material-icons text-sm mr-3 flex items-center justify-center">edit</span>
                                <div class="flex flex-col justify-center">
                                    <div class="text-sm font-medium leading-tight" style="font-family: 'Poppins', sans-serif; font-size: 11px;">Edit</div>
                                    <div class="text-xs text-gray-500 leading-tight" style="font-family: 'Poppins', sans-serif; font-size: 9px;">Boleh edit dokumen</div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <button onclick="sharingModal.removeMasjidShare('${masjid.kod_masjid}')"
                            class="hover:bg-red-50 rounded-full transition-colors"
                            style="padding: 8px !important; color: #dc2626 !important; width: 32px !important; height: 32px !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                        <span class="material-icons" style="font-size: 16px !important;">close</span>
                    </button>
                </div>
            `;
            container.appendChild(masjidElement);
        });
    }

    getPermissionText(level) {
        const permissions = {
            'view': 'Lihat',
            'comment': 'Komen', 
            'edit': 'Edit',
            'full_access': 'Akses penuh'
        };
        return permissions[level] || 'Lihat';
    }

    toggleAccessDropdown() {
        const dropdown = document.getElementById('accessDropdown');
        dropdown.classList.toggle('hidden');
    }

    hideAccessDropdown() {
        document.getElementById('accessDropdown').classList.add('hidden');
    }

    toggleViewerDropdown() {
        const dropdown = document.getElementById('viewerDropdown');
        dropdown.classList.toggle('hidden');
    }

    hideViewerDropdown() {
        const viewerDropdown = document.getElementById('viewerDropdown');
        if (viewerDropdown) {
            viewerDropdown.classList.add('hidden');
        }
    }

    setViewerRole(role) {
        this.currentViewerRole = role;
        this.hideViewerDropdown();
        
        // Update UI immediately
        this.updateViewerRoleUI(role);
        
        // Save to server if needed
        this.updateViewerRole(role);
    }

    updateViewerRoleUI(role) {
        // Update the button text
        const viewerButton = document.querySelector('#viewerDropdownText');
        if (viewerButton) {
            const roleTexts = {
                'viewer': 'Penonton',
                'commenter': 'Pengulas', 
                'editor': 'Editor'
            };
            viewerButton.textContent = roleTexts[role] || 'Penonton';
        }

        // Update checkmarks in dropdown
        document.querySelectorAll('#viewerDropdown .material-icons').forEach((icon, index) => {
            const roles = ['viewer', 'commenter', 'editor'];
            if (roles[index] === role) {
                icon.style.color = '#1a73e8';
            } else {
                icon.style.color = 'transparent';
            }
        });
    }

    async updateViewerRole(role) {
        // This would save the default role preference
        console.log('Updating default viewer role to:', role);
        // Implementation depends on your backend API
    }

    togglePermissionDropdown(kodMasjid) {
        // Hide all other permission dropdowns first
        document.querySelectorAll('[id^="permissionDropdown_"]').forEach(dropdown => {
            if (dropdown.id !== `permissionDropdown_${kodMasjid}`) {
                dropdown.classList.add('hidden');
            }
        });

        // Toggle the clicked dropdown
        const dropdown = document.getElementById(`permissionDropdown_${kodMasjid}`);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    hideAllPermissionDropdowns() {
        document.querySelectorAll('[id^="permissionDropdown_"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }

    async updateMasjidPermission(kodMasjid, newPermission) {
        if (!this.currentItem) return;

        try {
            const response = await fetch('/api/documents/sharing/update-permission', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    item_type: this.currentItem.type,
                    item_id: this.currentItem.id,
                    kod_masjid: kodMasjid,
                    permission_level: newPermission
                })
            });

            const data = await response.json();
            if (data.success) {
                window.showNotification('Kebenaran telah dikemaskini', 'success');
                this.hideAllPermissionDropdowns();
                this.loadSharingData(); // Reload to get updated list
            } else {
                window.showNotification(data.message || 'Ralat mengemas kini kebenaran', 'error');
            }
        } catch (error) {
            console.error('Error updating permission:', error);
            window.showNotification('Ralat mengemas kini kebenaran', 'error');
        }
    }

    async setAccessLevel(level) {
        this.currentAccessLevel = level;
        this.hideAccessDropdown();

        // Update UI immediately
        this.updateAccessLevelUI(level);

        // Save to server
        await this.updateAccessLevel(level);
    }

    updateAccessLevelUI(level) {
        const viewerContainer = document.getElementById('viewerDropdownContainer');
        const accessButton = document.getElementById('accessLevelText');
        const accessDescription = document.getElementById('accessLevelDescription');

        if (level === 'restricted') {
            // Hide viewer dropdown for restricted access
            if (viewerContainer) viewerContainer.style.display = 'none';
            if (accessButton) accessButton.textContent = 'Terhad';
            if (accessDescription) accessDescription.textContent = 'Hanya orang yang ditambah boleh akses';
        } else if (level === 'anyone_with_link') {
            // Show viewer dropdown for public access
            if (viewerContainer) viewerContainer.style.display = 'block';
            if (accessButton) accessButton.textContent = 'Sesiapa dengan pautan';
            if (accessDescription) accessDescription.textContent = 'Sesiapa di internet dengan pautan boleh lihat';
        }
    }



    async updateAccessLevel(level) {
        if (!this.currentItem) return;

        try {
            const response = await fetch(`/api/documents/sharing/access-level`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    item_type: this.currentItem.type,
                    item_id: this.currentItem.id,
                    access_level: level
                })
            });

            const data = await response.json();
            if (data.success) {
                window.showNotification('Tahap akses telah dikemaskini', 'success');
            } else {
                window.showNotification('Ralat mengemas kini tahap akses', 'error');
            }
        } catch (error) {
            console.error('Error updating access level:', error);
            window.showNotification('Ralat mengemas kini tahap akses', 'error');
        }
    }

    async addMasjidShare() {
        const kodMasjidInput = document.getElementById('kodMasjidInput');
        const kodMasjid = kodMasjidInput.value.trim();

        if (!kodMasjid) {
            window.showNotification('Sila masukkan Kod Masjid', 'error');
            return;
        }

        if (kodMasjid.length !== 6) {
            window.showNotification('Kod Masjid mesti 6 aksara', 'error');
            return;
        }

        try {
            const response = await fetch('/api/documents/sharing/share', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    item_type: this.currentItem.type,
                    item_id: this.currentItem.id,
                    kod_masjid: kodMasjid,
                    permission_level: 'view'
                })
            });

            const data = await response.json();
            if (data.success) {
                window.showNotification('Dokumen berjaya dikongsi', 'success');
                kodMasjidInput.value = '';
                this.loadSharingData(); // Reload to get updated list
            } else {
                window.showNotification(data.message || 'Ralat berkongsi dokumen', 'error');
            }
        } catch (error) {
            console.error('Error sharing document:', error);
            window.showNotification('Ralat berkongsi dokumen', 'error');
        }
    }

    async removeMasjidShare(kodMasjid) {

        try {
            const response = await fetch('/api/documents/sharing/unshare', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    item_type: this.currentItem.type,
                    item_id: this.currentItem.id,
                    kod_masjid: kodMasjid
                })
            });

            const data = await response.json();
            if (data.success) {
                window.showNotification('Perkongsian telah dihentikan', 'success');
                this.loadSharingData(); // Reload to get updated list
            } else {
                window.showNotification(data.message || 'Ralat menghentikan perkongsian', 'error');
            }
        } catch (error) {
            console.error('Error unsharing document:', error);
            window.showNotification('Ralat menghentikan perkongsian', 'error');
        }
    }

    async copyShareLink() {
        if (!this.currentItem) return;

        // Check if access level is restricted
        if (this.currentAccessLevel === 'restricted') {
            window.showNotification('Tidak boleh salin pautan untuk akses terhad', 'error');
            return;
        }

        try {
            const response = await fetch(`/api/documents/sharing/link/${this.currentItem.type}/${this.currentItem.id}`, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            if (data.success && data.data.share_link) {
                await navigator.clipboard.writeText(data.data.share_link);
                window.showNotification('Pautan telah disalin', 'success');
            } else {
                window.showNotification('Ralat mendapatkan pautan', 'error');
            }
        } catch (error) {
            console.error('Error copying share link:', error);
            window.showNotification('Ralat menyalin pautan', 'error');
        }
    }

    openSettings() {
        document.getElementById('sharingSettingsModal').classList.remove('hidden');
    }

    closeSettings() {
        document.getElementById('sharingSettingsModal').classList.add('hidden');
    }

    saveSettings() {
        // Get selected permission level
        const selectedPermission = document.querySelector('input[name="defaultPermission"]:checked').value;
        
        // Save settings logic here
        window.showNotification('Tetapan telah disimpan', 'success');
        this.closeSettings();
    }
}

// Global notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

    // Set colors based on type
    if (type === 'success') {
        notification.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        notification.className += ' bg-red-500 text-white';
    } else {
        notification.className += ' bg-blue-500 text-white';
    }

    notification.innerHTML = `
        <div class="flex items-center">
            <span class="material-icons text-sm mr-2">${type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info'}</span>
            <span style="font-family: 'Poppins', sans-serif; font-size: 12px;">${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Make showNotification available globally
window.showNotification = showNotification;

// Initialize sharing modal
const sharingModal = new SharingModal();

// Global functions for modal interactions
function openSharingModal(type, id, name) {
    sharingModal.open(type, id, name);
}

function closeSharingModal() {
    sharingModal.close();
}

function toggleAccessDropdown() {
    sharingModal.toggleAccessDropdown();
}

function setAccessLevel(level) {
    sharingModal.setAccessLevel(level);
}

function addMasjidShare() {
    sharingModal.addMasjidShare();
}

function copyShareLink() {
    sharingModal.copyShareLink();
}

function setViewerRole(role) {
    const viewerDropdown = document.getElementById('viewerDropdown');
    const viewerText = document.getElementById('viewerDropdownText');

    const roleMap = {
        'viewer': 'Viewer',
        'commenter': 'Commenter',
        'editor': 'Editor'
    };

    if (viewerText) viewerText.textContent = roleMap[role] || 'Viewer';
    if (viewerDropdown) viewerDropdown.classList.add('hidden');
}

function toggleAccessDropdown() {
    const dropdown = document.getElementById('accessDropdown');
    if (dropdown) dropdown.classList.toggle('hidden');

    // Close viewer dropdown if open
    const viewerDropdown = document.getElementById('viewerDropdown');
    if (viewerDropdown) viewerDropdown.classList.add('hidden');
}

function toggleViewerDropdown() {
    const dropdown = document.getElementById('viewerDropdown');
    if (dropdown) dropdown.classList.toggle('hidden');

    // Close access dropdown if open
    const accessDropdown = document.getElementById('accessDropdown');
    if (accessDropdown) accessDropdown.classList.add('hidden');
}

function openSharingSettings() {
    sharingModal.openSettings();
}

function closeSharingSettings() {
    sharingModal.closeSettings();
}

function saveSharingSettings() {
    sharingModal.saveSettings();
}

function toggleViewerDropdown() {
    sharingModal.toggleViewerDropdown();
}

function setViewerRole(role) {
    sharingModal.setViewerRole(role);
}
