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
                    version="v3.0"
                    title="Versi Semasa"
                    description="Kemaskini Major - Complete Kewangan, Asnaf & Kebajikan Modules"
                />

                <!-- Version Filter -->
                <x-release-notes.filter />

                <!-- Release Notes -->
                <div class="space-y-6">
                    <!-- Version 3.0 - Major Update -->
                    <x-release-notes.version
                        version="v3.0"
                        title="Kemaskini Major"
                        description="Complete Kewangan, Asnaf & Kebajikan Modules"
                        date="14 Disember 2025"
                        type="major"
                        :isLatest="true"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Kewangan Module -->
                                <x-release-notes.feature-section
                                    title="Modul Kewangan - Laporan & Transaksi"
                                    icon="account_balance"
                                    color="text-green-600"
                                >
                                    <x-release-notes.feature
                                        title="📊 3 TAB Baharu Laporan Kewangan"
                                        description="Penyata Pendapatan & Perbelanjaan (Income & Expenditure), Perbandingan Bulanan dengan percentage analysis, dan Laporan Mengikut Kategori (Top 5)"
                                        icon="assessment"
                                    />
                                    <x-release-notes.feature
                                        title="🏢 Filter Masjid untuk Super Admin"
                                        description="Dropdown 'Pilih Masjid' untuk Super Admin lihat laporan kewangan masjid lain - data isolation yang proper"
                                        icon="business"
                                    />
                                    <x-release-notes.feature
                                        title="💰 Enhanced Transaksi Kewangan"
                                        description="Improved show/edit pages dengan gradient cards, icons, dan historical balance calculation (Baki Pada Masa Transaksi)"
                                        icon="receipt_long"
                                    />
                                    <x-release-notes.feature
                                        title="📈 52 Sample Transactions"
                                        description="Realistic sample data untuk Jan-Feb 2025 dengan proper kategori mapping dan various payment methods"
                                        icon="data_usage"
                                    />
                                    <x-release-notes.feature
                                        title="🏷️ Kategori Integration - 8 Forms"
                                        description="Semua 8 forms (4 Kutipan Dana + 4 Perbelanjaan) kini ada kategori dropdown. Added Jenis Derma dan Jenis Bil untuk sub-categorization. Dynamic dan customizable dari Tetapan Kewangan"
                                        icon="category"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Permission System -->
                                <x-release-notes.feature-section
                                    title="Sistem Kebenaran & Akses Kawalan"
                                    icon="admin_panel_settings"
                                    color="text-blue-600"
                                >
                                    <x-release-notes.feature
                                        title="🔐 TAB-Level Permissions"
                                        description="Granular permission control untuk setiap TAB dalam Laporan Kewangan, Tetapan Kewangan, Tetapan Kebajikan, dan Tetapan Asnaf"
                                        icon="security"
                                    />
                                    <x-release-notes.feature
                                        title="🎯 Permission Matrix Expansion"
                                        description="Added 15+ new permissions untuk future modules: Inventori, Aset, Operasi, Pengurusan Masjid, dan Pentadbiran"
                                        icon="grid_view"
                                    />
                                    <x-release-notes.feature
                                        title="✅ Senarai Kumpulan - 23 Modules"
                                        description="Expanded dari 17 → 23 modules dengan 13 modules baru (Permohonan Zakat, Laporan Zakat, Tetapan Asnaf, Program Kebajikan, Penerima Bantuan, Permohonan Bantuan, Pembayaran Bantuan, Laporan Kebajikan, Tetapan Kebajikan, Akaun Bank, Transaksi Kewangan, Laporan Kewangan, Tetapan Kewangan). Reorganized dengan ASCII sorting dan proper module grouping"
                                        icon="reorder"
                                    />
                                    <x-release-notes.feature
                                        title="🛡️ Access Control Fixes"
                                        description="Fixed permission checks untuk Kebajikan views dan proper scope validation untuk all modules"
                                        icon="verified_user"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- AJK & Pengurusan Modules -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Modul Pengurusan - AJK, Asnaf & Kebajikan"
                                    icon="groups"
                                    color="text-orange-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-release-notes.feature-item
                                            title="👥 AJK - Laporan & Arkib"
                                            description="Complete AJK module dengan Laporan (active members) dan Arkib (inactive members) features"
                                        />
                                        <x-release-notes.feature-item
                                            title="🤲 Asnaf - Complete Workflow"
                                            description="Permohonan Zakat, Agihan Zakat, Laporan Zakat, dan Tetapan Asnaf dengan kategori integration"
                                        />
                                        <x-release-notes.feature-item
                                            title="❤️ Kebajikan - Full Module"
                                            description="Program Kebajikan, Penerima Bantuan, Permohonan Bantuan, Pembayaran Bantuan, Laporan Kebajikan, dan Tetapan Kebajikan"
                                        />
                                        <x-release-notes.feature-item
                                            title="⚙️ Tetapan Modules Enhancement"
                                            description="TAB-based settings untuk Asnaf, Kebajikan, dan Kewangan dengan kategori management"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Tetapan Modules with TABs -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Modul Tetapan - TAB-Based Configuration"
                                    icon="settings"
                                    color="text-indigo-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <x-release-notes.feature-item
                                            title="⚙️ Tetapan Kewangan"
                                            description="TAB-based settings: Kategori Pendapatan, Kategori Perbelanjaan, Jenis Derma, Jenis Bil. Dynamic management untuk categorization"
                                        />
                                        <x-release-notes.feature-item
                                            title="❤️ Tetapan Kebajikan"
                                            description="TAB-based settings: Had Bantuan, Workflow, Kategori, Permohonan, Pembayaran, Display, Tempoh Bantuan. Complete configuration system"
                                        />
                                        <x-release-notes.feature-item
                                            title="🤲 Tetapan Asnaf"
                                            description="TAB-based settings: Had Kifayah, Had Bantuan, Workflow, Kategori. Syariah-compliant configuration untuk 8 kategori asnaf"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Bantuan & Sokongan -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Bantuan & Sokongan - Documentation Updates"
                                    icon="help_center"
                                    color="text-teal-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-release-notes.feature-item
                                            title="❓ FAQ Updated to v3.0"
                                            description="Added 3 new categories (Modul Kewangan, Modul Asnaf & Kebajikan, Modul AJK Masjid) dengan 19 soalan baharu covering all v3.0 features"
                                        />
                                        <x-release-notes.feature-item
                                            title="📖 Panduan Pengguna Updated"
                                            description="Comprehensive user guide updated dengan step-by-step instructions untuk semua modules baharu termasuk Kewangan, Asnaf, Kebajikan, dan AJK"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>

                            <!-- UI/UX Improvements -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Penambahbaikan UI/UX"
                                    icon="palette"
                                    color="text-purple-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-release-notes.feature-item
                                            title="🎨 Table Text Color Fix"
                                            description="Fixed white text issue dalam TAB Kategori untuk Tetapan Kewangan, Kebajikan, dan Asnaf - semua text kini visible"
                                        />
                                        <x-release-notes.feature-item
                                            title="📄 Pagination Standardization"
                                            description="Standardized pagination across all modules - consistent 10 items per page dengan proper styling"
                                        />
                                        <x-release-notes.feature-item
                                            title="💳 Enhanced Show Pages"
                                            description="Gradient cards, Material Icons, dan improved layout untuk Transaksi Kewangan show/edit pages"
                                        />
                                        <x-release-notes.feature-item
                                            title="🎯 Full Width Filters"
                                            description="Responsive filter layout dengan flex-wrap untuk Laporan Kewangan - optimal space usage"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Bug Fixes -->
                            <div class="mt-6 p-4 bg-red-50 rounded border-l-4 border-red-500">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="material-icons text-sm text-red-600">bug_report</span>
                                    Critical Bug Fixes
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Variable Name Fixes:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Fixed $transaksi vs $transaksiKewangan inconsistency dalam show/edit views</li>
                                            <li>Fixed column name 'nama_masjid' to 'nama' untuk Masjid model</li>
                                            <li>Proper variable naming untuk single record vs collection</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Data & Display:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Fixed Laporan Kewangan data calculation untuk proper baki bersih</li>
                                            <li>Fixed empty folder display bila filter by file type</li>
                                            <li>Restored missing kaedah_bayaran field dalam edit form</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-4 p-4 bg-gray-50 rounded border-l-4 border-blue-500">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="material-icons text-sm text-blue-600">code</span>
                                    Technical Implementation
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Backend Enhancements:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Enhanced LaporanKewanganController dengan 3 new TAB data preparation</li>
                                            <li>Added RoleController permissions untuk future modules</li>
                                            <li>Improved TransaksiKewanganController dengan historical balance logic</li>
                                            <li>Migration untuk 52 realistic sample transactions</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Frontend Improvements:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>New TAB UI dengan tables, charts, dan proper permission wrapping</li>
                                            <li>Enhanced filter layout dengan flex-wrap dan min-width constraints</li>
                                            <li>CSS fixes dengan text-gray-700 dan font-medium untuk table headers</li>
                                            <li>Responsive design improvements across all modules</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Module Updates -->
                            <div class="mt-4 p-4 bg-green-50 rounded border-l-4 border-green-500">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="material-icons text-sm text-green-600">update</span>
                                    Module Updates Summary
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>👥 AJK Masjid:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>AJK Management ✅</li>
                                            <li>AJK Arkib ✅</li>
                                            <li>AJK Laporan ✅</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>🤲 Asnaf:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Asnaf ✅</li>
                                            <li>Permohonan Zakat ✅</li>
                                            <li>Agihan Zakat ✅</li>
                                            <li>Laporan Zakat ✅</li>
                                            <li>Tetapan Asnaf ✅</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>❤️ Kebajikan:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Program Kebajikan ✅</li>
                                            <li>Penerima Bantuan ✅</li>
                                            <li>Permohonan Bantuan ✅</li>
                                            <li>Pembayaran Bantuan ✅</li>
                                            <li>Laporan Kebajikan ✅</li>
                                            <li>Tetapan Kebajikan ✅</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>💰 Kewangan:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Akaun Bank ✅</li>
                                            <li>Transaksi Kewangan ✅</li>
                                            <li>Laporan Kewangan ✅</li>
                                            <li>Tetapan Kewangan ✅</li>
                                            <li>8 Forms Kategori ✅</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-green-200 text-xs text-gray-700">
                                    <div>
                                        <strong>⚙️ Pentadbiran & Sistem:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1 ml-4">
                                            <li>Senarai Kumpulan - 23 Modules (expanded dari 17) ✅</li>
                                            <li>Permission Matrix - TAB-level permissions ✅</li>
                                            <li>Access Control - Proper scope validation ✅</li>
                                            <li>Module Grouping - ASCII sorting dengan visual separators ✅</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 2.1 - Minor Update -->
                    <x-release-notes.version
                        version="v2.1"
                        title="Kemaskini Minor"
                        description="Document Filter Enhancement & UI Improvements"
                        date="21 September 2025"
                        type="minor"
                        :isLatest="false"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Document Management Enhancements"
                                    icon="filter_list"
                                    color="text-green-600"
                                >
                                    <x-release-notes.feature
                                        title="🎯 Smart File Type Filtering"
                                        description="Enhanced filter logic - folders now only appear when they contain documents of the selected file type (PDF, DOCX, etc.)"
                                        icon="filter_alt"
                                    />
                                    <x-release-notes.feature
                                        title="🏢 Masjid ID Display"
                                        description="Added Masjid ID information in document header helper text. Shows 'Super Admin' for super admin users and 'Masjid ID: [code]' for regular users"
                                        icon="business"
                                    />
                                    <x-release-notes.feature
                                        title="⭐ Improved Star Badge System"
                                        description="Enhanced star badge visibility with !important CSS rules for consistent display across all document and folder items"
                                        icon="star"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Technical Improvements -->
                                <x-release-notes.feature-section
                                    title="Technical Improvements"
                                    icon="code"
                                    color="text-blue-600"
                                >
                                    <x-release-notes.feature
                                        title="🔍 Enhanced Filter Logic"
                                        description="Implemented whereHas() query to filter folders based on document content, ensuring only relevant folders are displayed"
                                        icon="search"
                                    />
                                    <x-release-notes.feature
                                        title="🛡️ Data Isolation Consistency"
                                        description="Maintained strict masjid data isolation in folder filtering logic for security and proper multi-tenant architecture"
                                        icon="security"
                                    />
                                    <x-release-notes.feature
                                        title="🎨 CSS Optimization"
                                        description="Added !important rules for star badge opacity and hover effects to prevent style conflicts"
                                        icon="palette"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Bug Fixes -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Bug Fixes"
                                    icon="bug_report"
                                    color="text-red-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-release-notes.feature-item
                                            title="🐛 Fixed Empty Folder Display"
                                            description="Resolved issue where all folders were shown when filtering by file type, even if they contained no matching documents"
                                        />
                                        <x-release-notes.feature-item
                                            title="🐛 Fixed Star Badge Missing"
                                            description="Restored missing star badge for folder items that was accidentally removed during previous updates"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 2.0 - Major Update -->
                    <x-release-notes.version
                        version="v2.0"
                        title="Kemaskini Major"
                        description="Sistem Pengurusan Dokumen Lengkap dengan UI/UX Enhancements"
                        date="21 September 2025"
                        type="major"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="UI/UX Enhancements & Bug Fixes"
                                    icon="palette"
                                    color="text-blue-600"
                                >
                                    <x-release-notes.feature
                                        title="🎨 Enhanced Folder Color Picker"
                                        description="Improved Google Drive style color picker dengan 24 colors, live updates, dan proper check mark indicators"
                                        icon="color_lens"
                                    />
                                    <x-release-notes.feature
                                        title="🔧 Fixed Document Actions"
                                        description="Resolved 404 errors untuk delete dan spam actions - semua document management functions kini berfungsi dengan sempurna"
                                        icon="build"
                                    />
                                    <x-release-notes.feature
                                        title="✨ Live Color Selection"
                                        description="Real-time color picker updates tanpa browser refresh - check marks bertukar secara instant bila pilih warna baru"
                                        icon="refresh"
                                    />
                                    <x-release-notes.feature
                                        title="🎯 Improved Error Handling"
                                        description="Better error messages dengan proper HTTP status codes dan console logging untuk easier debugging"
                                        icon="error_outline"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Technical Improvements -->
                                <x-release-notes.feature-section
                                    title="Technical Improvements"
                                    icon="code"
                                    color="text-indigo-600"
                                >
                                    <x-release-notes.feature
                                        title="🔗 Route Parameter Fixes"
                                        description="Fixed route parameter mismatch - semua document actions kini menggunakan documentIdentifier untuk proper hash token resolution"
                                        icon="link"
                                    />
                                    <x-release-notes.feature
                                        title="🎨 CSS Optimization"
                                        description="Enhanced CSS dengan !important declarations untuk prevent conflicts, proper box-sizing, dan responsive design"
                                        icon="style"
                                    />
                                    <x-release-notes.feature
                                        title="⚡ JavaScript Performance"
                                        description="Optimized JavaScript functions dengan better DOM manipulation, efficient color picker updates, dan reduced reflows"
                                        icon="speed"
                                    />
                                    <x-release-notes.feature
                                        title="🛡️ Security Enhancements"
                                        description="Improved permission checking, proper access validation, dan secure document identifier resolution"
                                        icon="security"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Bug Fixes -->
                            <div class="mt-6 p-4 bg-red-50 rounded border-l-4 border-red-500">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="material-icons text-sm text-red-600">bug_report</span>
                                    Critical Bug Fixes
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Document Actions:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Fixed 404 errors untuk "Pindah ke tong sampah" action</li>
                                            <li>Fixed 404 errors untuk "Tandakan sebagai spam" action</li>
                                            <li>Resolved route parameter mismatch issues</li>
                                            <li>Proper hash token to Document model resolution</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Color Picker:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Colors now stay within proper boundaries</li>
                                            <li>Check marks update instantly without refresh</li>
                                            <li>Proper CSS sizing dengan overflow prevention</li>
                                            <li>Enhanced hover effects dan selection states</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-4 p-4 bg-gray-50 rounded border-l-4 border-blue-500">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="material-icons text-sm text-blue-600">code</span>
                                    Technical Implementation
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Backend Fixes:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Updated DocumentController methods untuk use documentIdentifier</li>
                                            <li>Enhanced findDocumentByIdentifier() functionality</li>
                                            <li>Improved error responses dengan proper JSON format</li>
                                            <li>Better permission checking dan access validation</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Frontend Enhancements:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Live color picker updates dengan updateColorPickerSelection()</li>
                                            <li>Enhanced CSS dengan comprehensive !important declarations</li>
                                            <li>Better error handling dengan proper HTTP status checking</li>
                                            <li>Optimized DOM manipulation untuk better performance</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.9 - Major Update -->
                    <x-release-notes.version
                        version="v1.9"
                        title="Kemaskini Major"
                        description="Sistem Perkongsian Dokumen & Kod Masjid Unik"
                        date="21 September 2025"
                        type="major"
                        bg-color="bg-emerald-50"
                        badge-color="bg-emerald-100 text-emerald-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Sistem Perkongsian Dokumen Google Drive Style"
                                    icon="share"
                                    color="text-emerald-600"
                                >
                                    <x-release-notes.feature
                                        title="🔗 Google Drive Style Document Sharing"
                                        description="Sistem perkongsian dokumen lengkap dengan modal sharing yang mirip Google Drive - support restricted dan public link sharing"
                                        icon="share"
                                    />
                                    <x-release-notes.feature
                                        title="🔒 Advanced Access Level Control"
                                        description="Dua tahap akses: 'Terhad' (hanya orang yang ditambah) dan 'Sesiapa dengan pautan' (public access)"
                                        icon="security"
                                    />
                                    <x-release-notes.feature
                                        title="🏢 Kod Masjid Sharing System"
                                        description="Share dokumen dengan masjid lain menggunakan Kod Masjid 6-digit dengan validation dan permission levels"
                                        icon="business"
                                    />
                                    <x-release-notes.feature
                                        title="🔐 Secure Token Management"
                                        description="32-karakter random tokens untuk public sharing dengan proper revoke/reuse logic"
                                        icon="vpn_key"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Improvements -->
                                <x-release-notes.feature-section
                                    title="Penambahbaikan Sistem"
                                    icon="upgrade"
                                    color="text-green-600"
                                >
                                    <x-release-notes.feature
                                        title="🆔 Kod Masjid Unik System"
                                        description="Sistem Kod Masjid yang unik untuk setiap masjid - tidak boleh duplicate, dengan validation dan error handling"
                                        icon="fingerprint"
                                    />
                                    <x-release-notes.feature
                                        title="🔄 Smart Token Reuse Logic"
                                        description="Sistem pintar yang reuse existing active share tokens untuk mengelakkan duplicate links"
                                        icon="refresh"
                                    />
                                    <x-release-notes.feature
                                        title="🛡️ Enhanced Security Controls"
                                        description="Proper access validation - 'Salin pautan' button hanya berfungsi untuk public access level"
                                        icon="shield"
                                    />
                                    <x-release-notes.feature
                                        title="📊 Real-time State Management"
                                        description="UI yang properly sync dengan database state - access level persistent selepas close/reopen modal"
                                        icon="sync"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Technical Details -->
                            <div class="mt-6 p-4 bg-gray-50 rounded border-l-4 border-emerald-500">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="material-icons text-sm text-emerald-600">code</span>
                                    Technical Implementation
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-700">
                                    <div>
                                        <strong>Frontend:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Google Drive style sharing modal dengan proper UI/UX</li>
                                            <li>Real-time access level state management</li>
                                            <li>Smart copy link functionality dengan validation</li>
                                            <li>Responsive design untuk mobile dan desktop</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Backend:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>DocumentShare model dengan comprehensive relationships</li>
                                            <li>API endpoints untuk sharing management</li>
                                            <li>Secure token generation dan validation</li>
                                            <li>Proper access control dan permission checking</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Features -->
                            <div class="mt-4 p-4 bg-red-50 rounded border-l-4 border-red-500">
                                <h4 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                                    <span class="material-icons text-sm text-red-600">security</span>
                                    Security Features
                                </h4>
                                <div class="text-xs text-gray-700 space-y-2">
                                    <div><strong>🔒 Access Control:</strong> Proper validation sebelum create/access share links</div>
                                    <div><strong>🚫 Token Revocation:</strong> Automatic revoke semua existing shares bila set ke 'Terhad'</div>
                                    <div><strong>🔐 Secure URLs:</strong> 32-karakter random tokens untuk maximum security</div>
                                    <div><strong>✅ State Validation:</strong> Backend validation untuk prevent unauthorized access</div>
                                </div>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.8 - Major Update -->
                    <x-release-notes.version
                        version="v1.8"
                        title="Kemaskini Major"
                        description="Sistem Pengurusan Dokumen Lengkap dengan Google Drive Style"
                        date="20 September 2025"
                        type="major"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Sistem Pengurusan Dokumen Lengkap"
                                    icon="folder"
                                    color="text-emerald-600"
                                >
                                    <x-release-notes.feature
                                        title="📁 Complete Document Management System"
                                        description="Sistem pengurusan dokumen lengkap dari awal dengan folder hierarchy, file upload, dan organization"
                                        icon="folder_open"
                                    />
                                    <x-release-notes.feature
                                        title="🎨 Google Drive Style Interface"
                                        description="UI/UX yang mirip Google Drive dengan grid/list view, context menus, dan drag-drop functionality"
                                        icon="view_module"
                                    />
                                    <x-release-notes.feature
                                        title="🔐 Google Drive Style URLs"
                                        description="Sistem URL dengan 32-karakter hash tokens untuk keselamatan maksimum"
                                        icon="link"
                                    />
                                    <x-release-notes.feature
                                        title="📊 Permission Matrix System"
                                        description="Matrix kebenaran lengkap untuk tambah, lihat, kemaskini, padam, tolak, terima, gantung, aktifkan"
                                        icon="admin_panel_settings"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Core Features -->
                                <x-release-notes.feature-section
                                    title="Ciri Utama Sistem"
                                    icon="star"
                                    color="text-blue-600"
                                >
                                    <x-release-notes.feature
                                        title="📂 Folder Management System"
                                        description="Sistem folder dengan hierarchy, warna custom, rename, delete, dan nested structure"
                                        icon="folder_special"
                                    />
                                    <x-release-notes.feature
                                        title="📄 File Upload & Management"
                                        description="Upload multiple files, preview, download, rename, delete dengan comprehensive file type support"
                                        icon="cloud_upload"
                                    />
                                    <x-release-notes.feature
                                        title="🎯 Context Menu System"
                                        description="Right-click context menus lengkap dalam Bahasa Melayu untuk semua actions"
                                        icon="more_vert"
                                    />
                                    <x-release-notes.feature
                                        title="🔍 Advanced File Type Icons"
                                        description="30+ file type icons dengan color coding untuk PDF, Word, Excel, images, videos, archives"
                                        icon="insert_drive_file"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- User Experience -->
                                <x-release-notes.feature-section
                                    title="Pengalaman Pengguna"
                                    icon="sentiment_satisfied"
                                    color="text-indigo-600"
                                >
                                    <x-release-notes.feature
                                        title="🎨 Real-time Color Updates"
                                        description="Perubahan warna folder berlaku serta-merta tanpa refresh halaman"
                                        icon="palette"
                                    />
                                    <x-release-notes.feature
                                        title="✅ Dynamic Color Picker"
                                        description="Tick mark menunjukkan warna folder yang betul dalam color picker"
                                        icon="colorize"
                                    />
                                    <x-release-notes.feature
                                        title="🌐 Bahasa Melayu Interface"
                                        description="Semua menu konteks dan interface dalam Bahasa Melayu sepenuhnya"
                                        icon="translate"
                                    />
                                    <x-release-notes.feature
                                        title="📱 Responsive Design"
                                        description="Interface yang responsive untuk desktop, tablet, dan mobile devices"
                                        icon="devices"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Security & Performance -->
                                <x-release-notes.feature-section
                                    title="Keselamatan & Prestasi"
                                    icon="security"
                                    color="text-red-600"
                                >
                                    <x-release-notes.feature
                                        title="🔐 Hash Token Security"
                                        description="32-karakter unique tokens menggantikan database IDs untuk keselamatan maksimum"
                                        icon="shield"
                                    />
                                    <x-release-notes.feature
                                        title="🚫 Enumeration Prevention"
                                        description="Hash tokens menghalang serangan sequential ID guessing"
                                        icon="gpp_good"
                                    />
                                    <x-release-notes.feature
                                        title="⚡ Real-time Updates"
                                        description="AJAX-based updates tanpa page refresh untuk better performance"
                                        icon="flash_on"
                                    />
                                    <x-release-notes.feature
                                        title="🔄 Backward Compatibility"
                                        description="Sokongan legacy ID untuk bookmarks dan links sedia ada"
                                        icon="history"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Database & Architecture -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Seni Bina & Database"
                                    icon="storage"
                                    color="text-orange-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <x-release-notes.feature
                                            title="🗄️ Complete Database Schema"
                                            description="Database design lengkap untuk documents, folders, shares, permissions dengan proper relationships"
                                            icon="database"
                                        />
                                        <x-release-notes.feature
                                            title="🔗 Multi-tenant Architecture"
                                            description="Data isolation per masjid dengan global scope dan permission-based access"
                                            icon="account_tree"
                                        />
                                        <x-release-notes.feature
                                            title="⚙️ Migration Commands"
                                            description="Artisan commands untuk generate hash tokens dan maintain data integrity"
                                            icon="terminal"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Upcoming Features -->
                            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-amber-800 mb-3 flex items-center">
                                    <span class="material-icons text-sm mr-2">schedule</span>
                                    Ciri Yang Akan Datang (Coming Soon)
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-amber-600">share</span>
                                        <span class="text-amber-800">Sharing & Collaboration</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-amber-600">link</span>
                                        <span class="text-amber-800">Copy Link Functionality</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-amber-600">info</span>
                                        <span class="text-amber-800">Folder Information & Details</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-amber-600">history</span>
                                        <span class="text-amber-800">Activity Tracking</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-amber-600">star</span>
                                        <span class="text-amber-800">Enhanced Starred System</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-xs text-amber-600">shortcut</span>
                                        <span class="text-amber-800">Quick Shortcuts</span>
                                    </div>
                                </div>
                            </div>

                            <!-- URL Examples -->
                            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <span class="material-icons text-sm mr-2 text-blue-600">link</span>
                                    Contoh URL Google Drive Style
                                </h4>
                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-red-600 font-mono">❌ Lama:</span>
                                        <code class="bg-red-100 text-red-800 px-2 py-1 rounded">http://localhost:8000/documents?folder=10</code>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-green-600 font-mono">✅ Folder:</span>
                                        <code class="bg-green-100 text-green-800 px-2 py-1 rounded">http://localhost:8000/documents?folder=mFtHDtFFLTa1FDmmWhLORdb4X9w00mWp</code>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-green-600 font-mono">✅ Dokumen:</span>
                                        <code class="bg-green-100 text-green-800 px-2 py-1 rounded">http://localhost:8000/documents/d/pSZr1yj5MvIRj2Xwn093OxKRq0bTC9fh</code>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-green-600 font-mono">✅ Direct:</span>
                                        <code class="bg-green-100 text-green-800 px-2 py-1 rounded">http://localhost:8000/documents/folders/mFtHDtFFLTa1FDmmWhLORdb4X9w00mWp</code>
                                    </div>
                                </div>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.7 - Major Update -->
                    <x-release-notes.version
                        version="v1.7"
                        title="Kemaskini Major"
                        description="Sistem Pengurusan Aset & Jualan Lengkap"
                        date="18 September 2025"
                        type="major"
                        bg-color="bg-emerald-50"
                        badge-color="bg-emerald-100 text-emerald-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Ciri Baharu"
                                    icon="new_releases"
                                    color="text-emerald-600"
                                >
                                    <x-release-notes.feature-item
                                        title="🏛️ Sistem Pengurusan Aset Lengkap"
                                        description="Menu navigasi aset dengan 5 kategori utama: Pengurusan Aset, Penyelenggaraan, Penyusutan & Nilai, Pelupusan Aset, dan Laporan Aset"
                                    />
                                    <x-release-notes.feature-item
                                        title="💰 Modul Jualan Komprehensif"
                                        description="Sistem jualan lengkap dalam Kewangan: Sebut Harga, Pesanan Jualan, Invois Proforma, Pesanan Penghantaran, Invois Jualan, Pulangan Jualan, Nota Kredit, dan Resit Rasmi"
                                    />
                                    <x-release-notes.feature-item
                                        title="📦 Integrasi Operasi-Aset-Kewangan"
                                        description="Workflow terintegrasi dari booking fasiliti (Operasi) → tracking pergerakan (Aset) → billing & payment (Kewangan)"
                                    />
                                    <x-release-notes.feature-item
                                        title="🔄 Sistem Pemindahan & Pergerakan Aset"
                                        description="Pemindahan Aset untuk proses transfer dan Pergerakan Aset untuk tracking & analytics dalam satu menu Pengurusan Aset"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Improvements -->
                                <x-release-notes.feature-section
                                    title="Penambahbaikan"
                                    icon="trending_up"
                                    color="text-blue-600"
                                >
                                    <x-release-notes.feature-item
                                        title="🎯 Menu Navigasi Terstruktur"
                                        description="Reorganisasi menu dengan nested submenus, colored ribbons, dan logical grouping untuk better user experience"
                                    />
                                    <x-release-notes.feature-item
                                        title="📋 Workflow Jualan Masjid"
                                        description="Proses jualan yang sesuai untuk konteks masjid: sewaan fasiliti, produk halal, perkhidmatan majlis, dan merchandise"
                                    />
                                    <x-release-notes.feature-item
                                        title="🏢 Pengurusan Modul Baru"
                                        description="Tambahan menu untuk Ahli Jawatankuasa Masjid, Asnaf, dan Kebajikan dengan submenu lengkap"
                                    />
                                    <x-release-notes.feature-item
                                        title="⚡ Performance & UX"
                                        description="Optimized navigation dengan proper Alpine.js implementation dan responsive design patterns"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Technical Improvements -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Penambahbaikan Teknikal"
                                    icon="code"
                                    color="text-purple-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-release-notes.feature-item
                                            title="🔧 Alpine.js Integration"
                                            description="Proper Alpine.js loading dalam app.js bundle untuk production stability"
                                        />
                                        <x-release-notes.feature-item
                                            title="🎨 Z-index Optimization"
                                            description="Fixed navigation z-index conflicts dengan high priority values (z-[9999])"
                                        />
                                        <x-release-notes.feature-item
                                            title="📱 Variable Name Conflicts"
                                            description="Resolved Alpine.js variable conflicts dengan unique naming untuk setiap submenu"
                                        />
                                        <x-release-notes.feature-item
                                            title="🚀 Production Ready"
                                            description="Fixed navigation overlapping issues dalam production environment"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Database & Architecture -->
                            <div class="mt-6">
                                <x-release-notes.feature-section
                                    title="Seni Bina & Database"
                                    icon="storage"
                                    color="text-orange-600"
                                >
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <x-release-notes.feature-item
                                            title="🗄️ Asset Management Schema"
                                            description="Database design untuk comprehensive asset tracking, depreciation, maintenance, dan disposal"
                                        />
                                        <x-release-notes.feature-item
                                            title="💳 Sales Transaction Schema"
                                            description="Complete sales workflow database dengan quotation, orders, invoices, dan receipts"
                                        />
                                        <x-release-notes.feature-item
                                            title="🔗 Integration Architecture"
                                            description="Seamless integration antara Operasi, Aset, dan Kewangan modules dengan proper data flow"
                                        />
                                    </div>
                                </x-release-notes.feature-section>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.6 - Major Update -->
                    <x-release-notes.version
                        version="v1.6"
                        title="Kemaskini Major"
                        description="Sistem Integrasi Lengkap - Email, Cuaca, API & Tetapan"
                        date="18 September 2025"
                        type="major"
                        bg-color="bg-emerald-50"
                        badge-color="bg-emerald-100 text-emerald-800"
                    >
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- New Features -->
                                <x-release-notes.feature-section
                                    title="Ciri Baharu"
                                    icon="add_circle"
                                    icon-color="text-indigo-600"
                                >
                                    <x-release-notes.feature-item
                                        title="Sistem Integrasi Email (SMTP)"
                                        description="Konfigurasi email lengkap dengan SMTP settings, authentication, encryption, dan test email functionality"
                                    />
                                    <x-release-notes.feature-item
                                        title="Integrasi Cuaca Komprehensif"
                                        description="Sistem cuaca dengan multiple providers (OpenWeatherMap, WeatherAPI), location settings, dan API key management"
                                    />
                                    <x-release-notes.feature-item
                                        title="Weather Widget dalam Navbar"
                                        description="Real-time weather display dalam navigation bar dengan temperature, condition, dan detailed tooltip"
                                    />
                                    <x-release-notes.feature-item
                                        title="Sistem Konfigurasi API Lengkap"
                                        description="API configuration dengan rate limiting, timeout settings, SSL verification, logging level, dan token management"
                                    />
                                    <x-release-notes.feature-item
                                        title="Tetapan Umum & Azan"
                                        description="General settings dengan azan audio file management, prayer time configurations, dan system preferences"
                                    />
                                    <x-release-notes.feature-item
                                        title="System Version Management"
                                        description="Automatic version display dalam footer yang sync dengan release notes untuk better version tracking"
                                    />
                                </x-release-notes.feature-section>

                                <!-- Major Fixes & Improvements -->
                                <x-release-notes.feature-section
                                    title="Pembaikan & Peningkatan"
                                    icon="build"
                                    icon-color="text-green-600"
                                >
                                    <x-release-notes.feature-item
                                        title="UV Index Display Fix"
                                        description="Fixed UV Index display dalam weather widget navbar untuk menunjukkan data yang tepat dan real-time"
                                    />
                                    <x-release-notes.feature-item
                                        title="Email Configuration Persistence"
                                        description="Resolved SMTP configuration saving issues dengan proper validation dan error handling"
                                    />
                                    <x-release-notes.feature-item
                                        title="Weather API Integration"
                                        description="Enhanced weather data fetching dengan fallback mechanisms dan error recovery"
                                    />
                                    <x-release-notes.feature-item
                                        title="API Configuration Form"
                                        description="Fixed form field enabling, data persistence, dan token management issues"
                                    />
                                    <x-release-notes.feature-item
                                        title="System Version Sync"
                                        description="Implemented automatic version updates dari release notes untuk consistent version display"
                                    />
                                    <x-release-notes.feature-item
                                        title="Multi-Provider Support"
                                        description="Added support untuk multiple weather providers dengan seamless switching capabilities"
                                    />
                                </x-release-notes.feature-section>
                            </div>

                            <!-- Integration Modules -->
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <h4 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                    <span class="material-icons text-blue-600 mr-2" style="font-size: 16px;">integration_instructions</span>
                                    Modul Integrasi Lengkap
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-blue-700">
                                    <div>
                                        <strong>Email Integration:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>SMTP configuration dengan authentication</li>
                                            <li>Email encryption (TLS/SSL) support</li>
                                            <li>Test email functionality</li>
                                            <li>From name dan reply-to settings</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Weather Integration:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Multiple weather providers support</li>
                                            <li>Real-time weather data dalam navbar</li>
                                            <li>UV Index, humidity, wind speed display</li>
                                            <li>Location-based weather configuration</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>API Management:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Complete API configuration system</li>
                                            <li>Sanctum token management</li>
                                            <li>Rate limiting dan timeout controls</li>
                                            <li>SSL verification settings</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>General Settings:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Azan audio file management</li>
                                            <li>Prayer time configurations</li>
                                            <li>System preferences</li>
                                            <li>Version management system</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Technical Architecture -->
                            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <h4 class="text-sm font-medium text-gray-800 mb-2 flex items-center">
                                    <span class="material-icons text-gray-600 mr-2" style="font-size: 16px;">code</span>
                                    Arkitektur Teknikal
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-600">
                                    <div>
                                        <strong>Backend Controllers:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>IntegrationController - Main integration hub</li>
                                            <li>WeatherController - Weather data management</li>
                                            <li>WeatherConfigurationController - Weather settings</li>
                                            <li>ApiConfigurationController - API management</li>
                                            <li>TetapanController - General settings</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Frontend Components:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Weather widget dalam double-navbar</li>
                                            <li>Integration tabs (Email, Weather, API)</li>
                                            <li>Real-time weather tooltip</li>
                                            <li>Form validation dan error handling</li>
                                            <li>Token management interface</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Database Models:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>WeatherConfiguration - Weather settings</li>
                                            <li>ApiConfiguration - API configurations</li>
                                            <li>Tetapan - General system settings</li>
                                            <li>Integration - Integration management</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>API Integrations:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>OpenWeatherMap API integration</li>
                                            <li>WeatherAPI.com support</li>
                                            <li>SMTP email service integration</li>
                                            <li>Laravel Sanctum token system</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Bug Fixes & Improvements -->
                            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-md">
                                <h4 class="text-sm font-medium text-green-800 mb-3 flex items-center">
                                    <span class="material-icons text-green-600 mr-2" style="font-size: 16px;">bug_report</span>
                                    Pembaikan Terkini & Peningkatan (v1.6 Updates)
                                </h4>
                                <div class="space-y-4">
                                    <!-- Email Integration Fixes -->
                                    <div>
                                        <h5 class="text-xs font-semibold text-green-700 mb-2">📧 Email Integration Fixes</h5>
                                        <ul class="list-disc list-inside text-xs text-green-600 space-y-1 ml-4">
                                            <li><strong>Email Test Status Tracking:</strong> Fixed email test status tidak update dalam database selepas test berjaya/gagal</li>
                                            <li><strong>Real-time Status Display:</strong> Email test status dan timestamp kini update secara real-time tanpa refresh page</li>
                                            <li><strong>Test Email Modal Text Color:</strong> Fixed text "Maklumat Konfigurasi" dan labels dalam modal yang warna putih tidak nampak</li>
                                            <li><strong>Database Integration:</strong> Proper integration dengan Tetapan model untuk smtp_last_test dan smtp_test_status</li>
                                        </ul>
                                    </div>

                                    <!-- API Integration Fixes -->
                                    <div>
                                        <h5 class="text-xs font-semibold text-green-700 mb-2">🔌 API Integration Fixes</h5>
                                        <ul class="list-disc list-inside text-xs text-green-600 space-y-1 ml-4">
                                            <li><strong>TEST API Function:</strong> Fixed TEST API button yang tidak berfungsi dengan proper error handling dan loading states</li>
                                            <li><strong>SYNC Data Function:</strong> Implemented complete SYNC Data functionality dengan multi-endpoint sync dan status tracking</li>
                                            <li><strong>Save Configuration Persistence:</strong> Fixed data tidak kekal di page selepas save - kini update secara real-time tanpa refresh</li>
                                            <li><strong>Success Notification:</strong> Added "Konfigurasi API berjaya dikemas kini" notification yang sebelumnya missing</li>
                                            <li><strong>Button Height Consistency:</strong> Fixed button height untuk sama dengan Email dan Weather tabs</li>
                                            <li><strong>Database Status Updates:</strong> API test dan sync kini update status dalam database dengan proper timestamp</li>
                                        </ul>
                                    </div>

                                    <!-- User Guide Improvements -->
                                    <div>
                                        <h5 class="text-xs font-semibold text-green-700 mb-2">📚 User Guide Improvements</h5>
                                        <ul class="list-disc list-inside text-xs text-green-600 space-y-1 ml-4">
                                            <li><strong>Navigation Button Alignment:</strong> Fixed icon dan text alignment dalam navigation buttons dengan proper flexbox</li>
                                            <li><strong>Font Size Optimization:</strong> Updated icon size kepada 20px dan font size kepada 13px untuk better readability</li>
                                            <li><strong>Dynamic Version Display:</strong> System version kini load dari Tetapan model secara dynamic</li>
                                            <li><strong>Comprehensive Content:</strong> Complete user guide untuk semua v1.6 features termasuk integration setup</li>
                                        </ul>
                                    </div>

                                    <!-- System Cleanup -->
                                    <div>
                                        <h5 class="text-xs font-semibold text-green-700 mb-2">🧹 System Cleanup</h5>
                                        <ul class="list-disc list-inside text-xs text-green-600 space-y-1 ml-4">
                                            <li><strong>Debug Code Removal:</strong> Comprehensive cleanup semua debug console.log, debug routes, dan test files</li>
                                            <li><strong>Production Ready:</strong> Removed debug routes (/debug-*, /test-*) dan debug logging statements</li>
                                            <li><strong>Clean Codebase:</strong> Maintained legitimate error logging sambil remove development debug code</li>
                                            <li><strong>Security Enhancement:</strong> Removed potential security risks dari debug routes dalam production</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Technical Implementation Details -->
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                                <h4 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                                    <span class="material-icons text-blue-600 mr-2" style="font-size: 16px;">settings</span>
                                    Technical Implementation Details
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-blue-700">
                                    <div>
                                        <strong>Backend Enhancements:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Enhanced ApiConfigurationController dengan testApi() dan syncData() methods</li>
                                            <li>Improved IntegrationController untuk load real test status dari database</li>
                                            <li>Updated routes dengan proper CSRF protection dan middleware</li>
                                            <li>Database integration untuk test status tracking</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Frontend Improvements:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Real-time data updates tanpa page refresh</li>
                                            <li>Consistent button styling across all integration tabs</li>
                                            <li>Enhanced error handling dengan user-friendly notifications</li>
                                            <li>Loading states dengan proper button animations</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>Database Updates:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>smtp_last_test dan smtp_test_status tracking</li>
                                            <li>api_last_test dan api_test_status implementation</li>
                                            <li>api_last_sync dan api_sync_status untuk sync operations</li>
                                            <li>Proper multi-tenant data isolation</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <strong>User Experience:</strong>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>Consistent notification system across all modules</li>
                                            <li>Improved modal text visibility dengan !important styling</li>
                                            <li>Better button height consistency (32px standard)</li>
                                            <li>Enhanced user guide dengan proper navigation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    </x-release-notes.version>

                    <!-- Version 1.5 - Major Update -->
                    <x-release-notes.version
                        version="v1.5"
                        title="Kemaskini Major"
                        description="Advanced Login Security & Role Management"
                        date="18 September 2025"
                        type="major"
                        bg-color="bg-emerald-50"
                        badge-color="bg-emerald-100 text-emerald-800"
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
                        bg-color="bg-emerald-50"
                        badge-color="bg-emerald-100 text-emerald-800"
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
