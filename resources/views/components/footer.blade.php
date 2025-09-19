<!-- Footer - Mobile Friendly -->
<footer class="bg-white border-t border-gray-200">
    <div class="container mx-auto px-1 md:px-1 py-2 md:py-2">
        <!-- Desktop Layout -->
        <div class="hidden md:flex justify-between items-center">
            <!-- Left - Copyright -->
            <div class="text-xs text-gray-500">
                © 2015 - {{ date('Y') }} Hak cipta terpelihara - Sistem Pengurusan Masjid
            </div>
            
            <!-- Right - Links -->
            <div class="flex space-x-4">
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">Penafian</a>
                <span class="text-xs text-gray-400">/</span>
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">Privasi</a>
                <span class="text-xs text-gray-400">/</span>
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">Terma Penggunaan</a>
                <span class="text-xs text-gray-400">/</span>
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">Peta Laman</a>
            </div>
        </div>
        
        <!-- Mobile Layout -->
        <div class="md:hidden space-y-4">
            <!-- Mobile - Copyright -->
            <div class="text-center">
                <div class="text-xs text-gray-500 mb-2">
                    © 2015 - {{ date('Y') }} Hak cipta terpelihara
                </div>
                <div class="text-xs text-gray-400">
                    Sistem Pengurusan Masjid
                </div>
            </div>
            
            <!-- Mobile - Links Grid -->
            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg">
                    Penafian
                </a>
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg">
                    Privasi
                </a>
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg">
                    Terma Penggunaan
                </a>
                <a href="#" class="text-xs text-gray-500 hover:text-gray-700 transition-colors text-center py-2 px-3 bg-gray-50 rounded-lg">
                    Peta Laman
                </a>
            </div>
            
            <!-- Mobile - Quick Info -->
            <div class="text-center pt-3 border-t border-gray-100">
                <div class="text-xs text-gray-400">
                    Versi {{ \App\Models\Tetapan::getSystemVersion() }} | Kemaskini Terakhir: {{ date('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
</footer> 