<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Keluaran - E-Masjid</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" style="font-family: 'Poppins', sans-serif;" x-data="releaseNotes()">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div x-data="{
                filterType: 'all',
                shouldShowRelease(type) {
                    return this.filterType === 'all' || this.filterType === type;
                }
            }" class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Nota Keluaran</h1>
                    <p class="text-xs text-gray-600">Sejarah kemaskini dan pembangunan Sistem E-Masjid</p>
                </div>

                <!-- Current Version Banner -->
                <x-release-notes.banner
                    version="v1.5"
                    title="Versi Semasa"
                    description="Kemaskini Major - Advanced Login Security & Role Management"
                />

                <!-- Version Filter -->
                <x-release-notes.filter />

                <!-- Release Notes -->
                <div class="space-y-6">
                    <!-- Version 1.5 - Major Update -->
                    <x-release-notes.version
                        version="v1.5"
                        title="Kemaskini Major"
                        description="Advanced Login Security & Role Management"
                        date="18 September 2025"
                        type="major"
                        bg-color="bg-purple-50"
                        badge-color="bg-purple-100 text-purple-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Ciri Baharu"
                                    icon="add_circle"
                                    icon-color="text-purple-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Advanced Login Validation"
                                        description="Multi-layer validation dengan priority-based error handling untuk login security"
                                    />
                                    <x-release-notes.feature-item
                                        title="Role Status Login Restriction"
                                        description="Users dengan role tidak aktif akan diblok dari login dengan immediate effect"
                                    />
                                    <x-release-notes.feature-item
                                        title="Dual Modal Error System"
                                        description="Separate modals untuk email verification dan role inactive dengan visual differentiation"
                                    />
                                    <x-release-notes.feature-item
                                        title="Smart Error Prioritization"
                                        description="Role status check mendapat priority lebih tinggi daripada email verification"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Improvements -->
                                <x-release-notes.feature-section
                                    title="Penambahbaikan"
                                    icon="upgrade"
                                    icon-color="text-purple-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Enhanced User Model Methods"
                                        description="Added hasActiveRole() method untuk cleaner code dan better maintainability"
                                    />
                                    <x-release-notes.feature-item
                                        title="Improved Modal UX Design"
                                        description="Color-coded modals dengan different icons untuk better user understanding"
                                    />
                                    <x-release-notes.feature-item
                                        title="Validation Logic Optimization"
                                        description="Streamlined login flow dengan proper error handling dan immediate logout"
                                    />
                                    <x-release-notes.feature-item
                                        title="Admin Role Management Impact"
                                        description="Role deactivation sekarang ada immediate effect pada semua users dengan role tersebut"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Security Enhancements -->
                            <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
                                <h4 class="text-sm font-medium text-red-800 mb-2 flex items-center">
                                    <span class="material-icons text-red-600 mr-2" style="font-size: 16px;">security</span>
                                    Penambahbaikan Keselamatan
                                </h4>
                                <ul class="text-xs text-red-700 space-y-1">
                                    <li>• <strong>Priority-Based Validation:</strong> Role status check sebelum email verification untuk better security</li>
                                    <li>• <strong>Immediate Role Effect:</strong> Role deactivation langsung block semua users tanpa delay</li>
                                    <li>• <strong>Enhanced Error Handling:</strong> Separate error keys untuk prevent confusion dan better debugging</li>
                                    <li>• <strong>Session Security:</strong> Immediate logout untuk unauthorized users dengan proper cleanup</li>
                                </ul>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <h4 class="text-sm font-medium text-gray-800 mb-2 flex items-center">
                                    <span class="material-icons text-gray-600 mr-2" style="font-size: 16px;">code</span>
                                    Butiran Teknikal
                                </h4>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• <strong>AuthController:</strong> Reordered validation priority dengan role status check first</li>
                                    <li>• <strong>User Model:</strong> Added hasActiveRole() method untuk better code organization</li>
                                    <li>• <strong>Blade Templates:</strong> Dual modal system dengan separate error keys (verification vs role_inactive)</li>
                                    <li>• <strong>Modal Design:</strong> Color-coded system - Orange untuk email issues, Red untuk role issues</li>
                                    <li>• <strong>Error Handling:</strong> Enhanced error messages dengan proper user guidance</li>
                                </ul>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.4 - Major Update -->
                    <x-release-notes.version
                        version="v1.4"
                        title="Kemaskini Major"
                        description="Email Verification & Security Enhancements"
                        date="17 September 2025"
                        type="major"
                        bg-color="bg-green-50"
                        badge-color="bg-green-100 text-green-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Ciri Baharu"
                                    icon="add_circle"
                                    icon-color="text-green-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Email Verification System"
                                        description="Sistem pengesahan email untuk keselamatan login - user pending tidak boleh login"
                                    />
                                    <x-release-notes.feature-item
                                        title="Login Restriction Modal"
                                        description="Modal popup error yang cantik untuk user yang belum disahkan"
                                    />
                                    <x-release-notes.feature-item
                                        title="Admin Verification Controls"
                                        description="Admin boleh verify/unverify user dengan one-click action buttons"
                                    />
                                    <x-release-notes.feature-item
                                        title="Role Filtering Enhancement"
                                        description="Admin Masjid hanya nampak roles untuk masjid mereka (no Super Admin access)"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Improvements -->
                                <x-release-notes.feature-section
                                    title="Penambahbaikan"
                                    icon="upgrade"
                                    icon-color="text-orange-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Permission-Based UI Controls"
                                        description="Show page buttons (Edit/Delete) sekarang respect role permissions"
                                    />
                                    <x-release-notes.feature-item
                                        title="Performance Optimization"
                                        description="Removed unnecessary Alpine.js CDN dependencies untuk faster loading"
                                    />
                                    <x-release-notes.feature-item
                                        title="Form Auto-Selection"
                                        description="Create user form auto-select masjid dan role yang sesuai"
                                    />
                                    <x-release-notes.feature-item
                                        title="Data Validation Enhancement"
                                        description="Improved role-masjid validation dengan proper type handling"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Security Improvements -->
                            <div class="mt-6 p-4 bg-red-50 rounded border border-red-200">
                                <h4 class="text-sm font-semibold text-red-800 mb-2 flex items-center">
                                    <span class="material-icons mr-2 text-red-600" style="font-size: 16px !important;">security</span>
                                    Penambahbaikan Keselamatan
                                </h4>
                                <ul class="text-xs text-red-700 space-y-1">
                                    <li>• <strong>Login Security:</strong> Immediate logout untuk unverified users</li>
                                    <li>• <strong>Privilege Escalation Prevention:</strong> Admin Masjid tidak boleh create Super Admin</li>
                                    <li>• <strong>UI Security:</strong> Hide action buttons based on permissions</li>
                                    <li>• <strong>Data Integrity:</strong> Enhanced role-masjid validation</li>
                                </ul>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-6 p-4 bg-gray-50 rounded border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                    <span class="material-icons mr-2 text-gray-600" style="font-size: 16px !important;">code</span>
                                    Butiran Teknikal
                                </h4>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• <strong>AuthController:</strong> Added email verification check in login method</li>
                                    <li>• <strong>UserController:</strong> Enhanced role filtering logic untuk create/edit forms</li>
                                    <li>• <strong>Blade Templates:</strong> Added permission checks untuk show page buttons</li>
                                    <li>• <strong>Modal System:</strong> Improved centering dan responsive design</li>
                                    <li>• <strong>Performance:</strong> Selective Alpine.js loading untuk faster page loads</li>
                                </ul>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.3 - Major Update -->
                    <x-release-notes.version
                        version="v1.3"
                        title="Kemaskini Major"
                        description="Permission System & Data Isolation"
                        date="17 September 2025"
                        type="major"
                        bg-color="bg-blue-50"
                        badge-color="bg-blue-100 text-blue-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Ciri Baharu"
                                    icon="add_circle"
                                    icon-color="text-blue-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Multi-Masjid Data Isolation"
                                        description="Setiap masjid hanya dapat akses data mereka sendiri dengan complete isolation"
                                    />
                                    <x-release-notes.feature-item
                                        title="Permission-Based Access Control"
                                        description="Sistem kebenaran berdasarkan role dengan granular permissions"
                                    />
                                    <x-release-notes.feature-item
                                        title="Dynamic Statistics Cards"
                                        description="Cards yang menunjukkan data real-time berdasarkan user permissions"
                                    />
                                    <x-release-notes.feature-item
                                        title="Role Management System"
                                        description="Pengurusan kumpulan pengguna dengan system roles dan custom roles"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Major Improvements -->
                                <x-release-notes.feature-section
                                    title="Penambahbaikan Major"
                                    icon="upgrade"
                                    icon-color="text-green-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Full Width Statistics Layout"
                                        description="Cards menggunakan full container width dengan responsive grid system"
                                    />
                                    <x-release-notes.feature-item
                                        title="Consistent Data Format"
                                        description="Unified array structure untuk semua statistics components"
                                    />
                                    <x-release-notes.feature-item
                                        title="Clean User Interface"
                                        description="Simplified cards dengan focus pada essential metrics sahaja"
                                    />
                                    <x-release-notes.feature-item
                                        title="Scalable Architecture"
                                        description="Future-proof design yang boleh handle unlimited roles dan masjids"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-sm">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-gray-600">security</span>
                                    Security & Data Isolation
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Permission System:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>CheckPermission middleware implementation</li>
                                            <li>Role-based access control (RBAC)</li>
                                            <li>Super Admin vs Masjid Admin isolation</li>
                                            <li>Granular permission checking</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Data Architecture:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>masjid_id filtering pada semua queries</li>
                                            <li>Dynamic statistics calculation</li>
                                            <li>Real-time data reflection</li>
                                            <li>Consistent array format across controllers</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Module Updates -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-sm">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-blue-600">dashboard</span>
                                    Module-Specific Updates
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Senarai-Kumpulan:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>3 dynamic cards: Jumlah, Aktif, Tersuai</li>
                                            <li>Masjid-specific role filtering</li>
                                            <li>Real database counts</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Senarai-Pengguna:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Fixed 3 cards: Jumlah, Belum Disahkan, Disahkan</li>
                                            <li>Removed role-based cards for scalability</li>
                                            <li>Clean consistent layout</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Senarai-Masjid:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>6 status cards: Total, Active, Pending, etc</li>
                                            <li>Dynamic card count based on data</li>
                                            <li>Full width responsive layout</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.2 - Minor Update -->
                    <x-release-notes.version
                        version="v1.2"
                        title="Kemaskini Minor"
                        description="Icon Consistency & System Stability"
                        date="16 September 2025"
                        type="minor"
                        bg-color="bg-green-50"
                        badge-color="bg-green-100 text-green-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Penambahbaikan"
                                    icon="upgrade"
                                    icon-color="text-green-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Button Icon Consistency"
                                        description="Semua icon dalam button sekarang 16px dengan !important untuk consistency"
                                    />
                                    <x-release-notes.feature-item
                                        title="System Status Stability"
                                        description="Fixed Spatie Health integration dengan mock data fallback"
                                    />
                                    <x-release-notes.feature-item
                                        title="Dashboard Error Resolution"
                                        description="Resolved Internal Server Error pada dashboard overview"
                                    />
                                    <x-release-notes.feature-item
                                        title="Show View Enhancement"
                                        description="Complete show view dengan consistent height patterns"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Bug Fixes -->
                                <x-release-notes.feature-section
                                    title="Pembetulan Bug"
                                    icon="bug_report"
                                    icon-color="text-red-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Maps Button Height"
                                        description="Fixed height inconsistency pada Lihat di Maps button"
                                    />
                                    <x-release-notes.feature-item
                                        title="CSS Override Issues"
                                        description="Resolved global CSS conflicts dengan inline styles !important"
                                    />
                                    <x-release-notes.feature-item
                                        title="Missing Information Sections"
                                        description="Added Maklumat Hubungan, Tambahan & Pendaftar sections"
                                    />
                                    <x-release-notes.feature-item
                                        title="Service Layer Errors"
                                        description="Fixed undefined method calls dalam SystemStatusService"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-sm">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-gray-600">code</span>
                                    Technical Changes
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Icon Standardization:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>All button icons: 16px with !important</li>
                                            <li>Covers: Index, Create, Edit, Show pages</li>
                                            <li>Inline styles override CSS conflicts</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>System Stability:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Mock data fallback for health checks</li>
                                            <li>Error handling dalam service layer</li>
                                            <li>Dashboard accessibility restored</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.1 - Minor Update -->
                    <x-release-notes.version
                        version="v1.1"
                        title="Kemaskini Minor"
                        description="Status Sistem & Penambahbaikan UI"
                        type="minor"
                        bg-color="bg-green-50"
                        badge-color="bg-green-100 text-green-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Ciri Baharu"
                                    icon="add_circle"
                                    icon-color="text-green-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Status Sistem"
                                        description="Pemantauan kesihatan sistem menggunakan Spatie Laravel Health"
                                    />
                                    <x-release-notes.feature-item
                                        title="Health Checks"
                                        description="9 pemeriksaan sistem (Database, Cache, Queue, Disk Space, dll)"
                                    />
                                    <x-release-notes.feature-item
                                        title="Google Maps Integration"
                                        description="Link lokasi masjid ke Google Maps dengan new tab"
                                    />
                                    <x-release-notes.feature-item
                                        title="Date Picker Component"
                                        description="Custom date picker dengan white color scheme"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Improvements -->
                                <x-release-notes.feature-section
                                    title="Penambahbaikan"
                                    icon="upgrade"
                                    icon-color="text-green-600"
                                >
                                    <x-release-notes.feature-item
                                        title="UI Consistency"
                                        description="Unified blue-purple gradient across bantuan pages"
                                    />
                                    <x-release-notes.feature-item
                                        title="Button Icons"
                                        description="Proportional icon sizing (text-xs) untuk better visual balance"
                                    />
                                    <x-release-notes.feature-item
                                        title="Modal Overlays"
                                        description="30% black transparent background untuk elegant appearance"
                                    />
                                    <x-release-notes.feature-item
                                        title="Storage System"
                                        description="Proper file attachment handling dengan symbolic links"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <x-release-notes.feature-section
                                    title="Butiran Teknikal"
                                    icon="code"
                                    icon-color="text-green-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-gray-50 p-3 rounded-sm">
                                            <div class="text-xs font-medium text-gray-900 mb-1">Health Monitoring</div>
                                            <div class="text-xs text-gray-600">Spatie Laravel Health package dengan 9 health checks</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-sm">
                                            <div class="text-xs font-medium text-gray-900 mb-1">Maps Integration</div>
                                            <div class="text-xs text-gray-600">Google Maps links dengan coordinate-based navigation</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-sm">
                                            <div class="text-xs font-medium text-gray-900 mb-1">UI Components</div>
                                            <div class="text-xs text-gray-600">Custom date picker dengan cross-browser compatibility</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-sm">
                                            <div class="text-xs font-medium text-gray-900 mb-1">Design System</div>
                                            <div class="text-xs text-gray-600">Consistent gradient colors dan proportional icons</div>
                                        </div>
                                    </div>
                                </x-release-notes.feature-section>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.0 - Initial Release -->
                    <x-release-notes.version
                        version="v1.0"
                        title="Versi Beta"
                        description="Pelancaran beta Sistem E-Masjid dengan fungsi asas"
                        date="15/01/2025"
                        type="initial"
                        bg-color="bg-green-50"
                        badge-color="bg-green-100 text-green-800"
                    >

                        <!-- Core Features -->
                        <div class="mb-6">
                            <x-release-notes.feature-section
                                title="Core Features"
                                icon="star"
                                icon-color="text-blue-600"
                            >
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <x-release-notes.feature-item
                                        icon="check_circle"
                                        icon-color="text-green-600"
                                    >Sistem pengurusan senarai masjid</x-release-notes.feature-item>
                                    <x-release-notes.feature-item
                                        icon="check_circle"
                                        icon-color="text-green-600"
                                    >CRUD lengkap untuk data masjid</x-release-notes.feature-item>
                                    <x-release-notes.feature-item
                                        icon="check_circle"
                                        icon-color="text-green-600"
                                    >Sistem approval workflow</x-release-notes.feature-item>
                                    <x-release-notes.feature-item
                                        icon="check_circle"
                                        icon-color="text-green-600"
                                    >Beautiful modal components</x-release-notes.feature-item>
                                    <x-release-notes.feature-item
                                        icon="check_circle"
                                        icon-color="text-green-600"
                                    >File attachment system</x-release-notes.feature-item>
                                    <x-release-notes.feature-item
                                        icon="check_circle"
                                        icon-color="text-green-600"
                                    >Interactive Google Maps</x-release-notes.feature-item>
                                </div>
                            </x-release-notes.feature-section>
                        </div>

                        <!-- Database -->
                        <x-release-notes.feature-section
                            title="Database"
                            icon="storage"
                            icon-color="text-purple-600"
                            class="mb-6"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Struktur database lengkap</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Seeder untuk data contoh</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Migrations untuk semua modul</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Relationships antar modul</x-release-notes.feature-item>
                            </div>
                        </x-release-notes.feature-section>

                        <!-- UI/UX -->
                        <x-release-notes.feature-section
                            title="UI/UX"
                            icon="palette"
                            icon-color="text-pink-600"
                            class="mb-6"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Poppins font implementation</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Tailwind CSS framework</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Component-based architecture</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Responsive design</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Beautiful date picker</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="check_circle" icon-color="text-green-600">Material Icons integration</x-release-notes.feature-item>
                            </div>
                        </x-release-notes.feature-section>

                        <!-- Technical Stack -->
                        <x-release-notes.feature-section
                            title="Maklumat Teknikal"
                            icon="code"
                            icon-color="text-orange-600"
                            class="mb-6"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <x-release-notes.feature-item icon="code" icon-color="text-blue-600">Laravel 12.x framework</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="code" icon-color="text-blue-600">PHP 8.4 compatibility</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="code" icon-color="text-blue-600">MySQL database</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="code" icon-color="text-blue-600">Alpine.js untuk JavaScript</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="code" icon-color="text-blue-600">Blade templating engine</x-release-notes.feature-item>
                                <x-release-notes.feature-item icon="code" icon-color="text-blue-600">Google Maps API integration</x-release-notes.feature-item>
                            </div>
                        </x-release-notes.feature-section>
                    </x-release-notes.version>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function releaseNotes() {
            return {
                filterType: 'all',
                
                shouldShowRelease(type) {
                    return this.filterType === 'all' || this.filterType === type;
                }
            }
        }
    </script>
</body>
</html>
