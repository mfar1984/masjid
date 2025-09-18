<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Masjid</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Suppress Vite Development Logs -->
    <script>
        // Override console methods to suppress Vite/HMR messages
        (function() {
            const originalLog = console.log;
            const originalInfo = console.info;
            const originalWarn = console.warn;

            console.log = function(...args) {
                const message = args.join(' ');
                if (message.includes('[vite]') ||
                    message.includes('[HMR]') ||
                    message.includes('connecting') ||
                    message.includes('connected') ||
                    message.includes('client:') ||
                    message.includes('[DOM]') ||
                    message.includes('autocomplete')) {
                    return; // Suppress Vite development and DOM messages
                }
                return originalLog.apply(console, args);
            };

            console.info = function(...args) {
                const message = args.join(' ');
                if (message.includes('[vite]') ||
                    message.includes('[HMR]') ||
                    message.includes('connecting') ||
                    message.includes('connected') ||
                    message.includes('client:') ||
                    message.includes('[DOM]') ||
                    message.includes('autocomplete')) {
                    return; // Suppress Vite development and DOM messages
                }
                return originalInfo.apply(console, args);
            };

            console.warn = function(...args) {
                const message = args.join(' ');
                if (message.includes('[vite]') ||
                    message.includes('[HMR]') ||
                    message.includes('connecting') ||
                    message.includes('connected') ||
                    message.includes('client:') ||
                    message.includes('[DOM]') ||
                    message.includes('autocomplete')) {
                    return; // Suppress Vite development and DOM messages
                }
                return originalWarn.apply(console, args);
            };
        })();
    </script>
</head>
<body class="bg-gray-100 font-sans" data-theme="corporate">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <img src="{{ asset('images/logo.svg') }}" class="mx-auto h-12 w-12" alt="Logo">
                <h2 class="mt-6 text-lg md:text-xl font-bold text-gray-800">Sign in to your account</h2>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700">Email address</label>
                        <input id="email" name="email" type="email" required
                               autocomplete="email"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('email') }}"
                               placeholder="Enter your email">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                               autocomplete="current-password"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Enter your password">
                    </div>
                </div>
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Email Verification Error Modal -->
    @error('verification')
        <div id="verificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-md shadow-lg max-w-md w-full mx-4">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 mb-4">
                        <span class="material-icons text-orange-600" style="font-size: 24px !important;">pending</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Akaun Belum Disahkan</h3>
                    <p class="text-sm text-gray-500 mb-6">{{ $message }}</p>
                    <button id="closeVerificationModal" class="w-full px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Close verification modal when clicking close button or outside modal
            document.getElementById('closeVerificationModal').onclick = function() {
                document.getElementById('verificationModal').style.display = 'none';
            }

            window.onclick = function(event) {
                const modal = document.getElementById('verificationModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        </script>
    @enderror

    <!-- Role Inactive Error Modal -->
    @error('role_inactive')
        <div id="roleInactiveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-md shadow-lg max-w-md w-full mx-4">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <span class="material-icons text-red-600" style="font-size: 24px !important;">block</span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Kumpulan Tidak Aktif</h3>
                    <p class="text-sm text-gray-500 mb-6">{{ $message }}</p>
                    <button id="closeRoleModal" class="w-full px-4 py-2 bg-red-500 text-white text-sm font-medium rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Close role inactive modal when clicking close button or outside modal
            document.getElementById('closeRoleModal').onclick = function() {
                document.getElementById('roleInactiveModal').style.display = 'none';
            }

            window.onclick = function(event) {
                const modal = document.getElementById('roleInactiveModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        </script>
    @enderror
</body>
</html>