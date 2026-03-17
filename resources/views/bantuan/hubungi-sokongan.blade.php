<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Hubungi Sokongan - E-Masjid' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
            font-size: 12px !important;
        }
        
        .chat-container {
            height: calc(100vh - 200px);
        }
        
        .chat-messages {
            height: calc(100vh - 350px);
            overflow-y: auto;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            margin-bottom: 12px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .message-sent {
            background: #3b82f6;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 6px;
        }
        
        .message-received {
            background: #f3f4f6;
            color: #374151;
            margin-right: auto;
            border-bottom-left-radius: 6px;
        }
        
        .chat-input {
            border: 1px solid #d1d5db;
            border-radius: 24px;
            padding: 12px 20px;
            font-size: 12px;
            resize: none;
            outline: none;
        }
        
        .chat-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .send-button {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .send-button:hover {
            background: #2563eb;
        }
        
        .send-button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* Mode Toggle Buttons */
        .mode-toggle-btn {
            display: inline-flex !important;
            align-items: center !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
            border: 1px solid #e5e7eb !important;
            background: white !important;
            color: #6b7280 !important;
        }

        .mode-toggle-btn.active {
            background: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }

        .mode-toggle-btn:hover:not(.active) {
            background: #f3f4f6 !important;
            border-color: #d1d5db !important;
        }

        .mode-toggle-btn .material-icons {
            font-size: 1.125rem !important;
            margin-right: 8px !important;
            width: 18px !important;
            height: 18px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            vertical-align: middle !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <!-- Navigation -->
    <x-double-navbar />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Chat Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
            <div class="flex h-full">
                <!-- Left Sidebar Navigation -->
                <div class="w-64 bg-gray-50 border-r border-gray-200 p-4">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="material-icons text-blue-600">support_agent</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Sokongan Masjid</h3>
                                <p class="text-xs text-gray-500">Hubungi pasukan sokongan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">AKSES TERSEDIA</h4>
                            <div class="space-y-1">
                                <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 bg-blue-50 rounded-lg">
                                    <span class="material-icons text-lg mr-3">chat</span>
                                    Chat Sokongan
                                </a>
                                <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <span class="material-icons text-lg mr-3">history</span>
                                    Sejarah Chat
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                
                <!-- Right Content Area - Chat & Ticket Interface -->
                <div class="flex-1 flex flex-col">
                    <!-- Header Section - Same Style as Support Dashboard -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="material-icons text-white">support_agent</span>
                                </div>
                                <div>
                                    <h1 class="text-lg font-semibold text-gray-900">Sokongan Masjid</h1>
                                    <p class="text-sm text-gray-600">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse inline-block"></span>
                                        Pasukan sokongan sedia membantu
                                    </p>
                                </div>
                            </div>

                            <!-- Mode Toggle Buttons -->
                            <div class="flex items-center space-x-3">
                                <button id="chatModeBtn" class="mode-toggle-btn active">
                                    <span class="material-icons">chat</span>
                                    Chat Langsung
                                </button>
                                <button id="ticketModeBtn" class="mode-toggle-btn">
                                    <span class="material-icons">confirmation_number</span>
                                    Sistem Tiket
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Content Area -->
                    <div id="chatContent" class="flex-1 flex flex-col">
                        <!-- Chat Messages Area -->
                        <div class="flex-1 p-6 chat-messages" id="chatMessages">
                        <!-- Welcome Message -->
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-icons text-blue-600 text-2xl">support_agent</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Selamat datang ke Chat Sokongan</h3>
                            <p class="text-gray-600 mb-4">Pasukan sokongan kami sedia membantu anda dengan sebarang pertanyaan atau masalah.</p>
                            <div class="text-sm text-gray-500">
                                <p>Waktu operasi: Isnin - Jumaat, 9:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                        
                        <!-- Sample Messages -->
                        <div class="message-bubble message-received">
                            <div class="font-medium text-xs text-gray-500 mb-1">Pasukan Sokongan</div>
                            <div>Assalamualaikum! Selamat datang ke sistem sokongan E-Masjid. Bagaimana kami boleh membantu anda hari ini?</div>
                            <div class="text-xs text-gray-400 mt-1">2 minit yang lalu</div>
                        </div>
                    </div>
                    
                    <!-- Chat Input Area -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-white">
                        <div class="flex items-end space-x-3">
                            <div class="flex-1">
                                <textarea 
                                    id="messageInput" 
                                    placeholder="Taip mesej anda di sini..." 
                                    class="chat-input w-full"
                                    rows="1"
                                    onkeypress="handleKeyPress(event)"
                                ></textarea>
                            </div>
                            <button 
                                id="sendButton" 
                                onclick="sendMessage()" 
                                class="send-button"
                                title="Hantar mesej"
                            >
                                <span class="material-icons">send</span>
                            </button>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-2 mt-3">
                            <button class="flex items-center px-3 py-1 text-xs text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors">
                                <span class="material-icons text-sm mr-1">attach_file</span>
                                Lampiran
                            </button>
                            <button class="flex items-center px-3 py-1 text-xs text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors">
                                <span class="material-icons text-sm mr-1">help</span>
                                FAQ
                            </button>
                        </div>
                    </div>
                    </div>

                    <!-- Ticket Content Area -->
                    <div id="ticketContent" class="flex-1 flex flex-col" style="display: none;">
                        <!-- Ticket Header -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="material-icons text-blue-600 text-2xl">confirmation_number</span>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Sistem Tiket Sokongan</h3>
                                <p class="text-gray-600 mb-6">Buat tiket baru untuk mendapatkan bantuan dari pasukan sokongan kami.</p>

                                <!-- Ticket Actions -->
                                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                        <span class="material-icons text-lg mr-2">add</span>
                                        Buat Tiket Baru
                                    </button>
                                    <button class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                                        <span class="material-icons text-lg mr-2">list</span>
                                        Lihat Tiket Saya
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Info -->
                        <div class="flex-1 p-6">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <span class="material-icons text-yellow-600 mr-3 mt-0.5">info</span>
                                    <div>
                                        <h4 class="font-medium text-yellow-800 mb-1">Nota:</h4>
                                        <p class="text-sm text-yellow-700">
                                            Tiket anda akan dihantarkan kepada Super Admin untuk tindakan lanjut.
                                            Anda akan menerima notifikasi apabila tiket anda telah diproses.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />
    
    <script>
        // Mode Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const chatModeBtn = document.getElementById('chatModeBtn');
            const ticketModeBtn = document.getElementById('ticketModeBtn');
            const chatContent = document.getElementById('chatContent');
            const ticketContent = document.getElementById('ticketContent');

            chatModeBtn.addEventListener('click', function() {
                // Toggle active states
                chatModeBtn.classList.add('active');
                ticketModeBtn.classList.remove('active');

                // Show/hide content
                chatContent.style.display = 'flex';
                ticketContent.style.display = 'none';
            });

            ticketModeBtn.addEventListener('click', function() {
                // Toggle active states
                ticketModeBtn.classList.add('active');
                chatModeBtn.classList.remove('active');

                // Show/hide content
                chatContent.style.display = 'none';
                ticketContent.style.display = 'flex';
            });
        });

        function handleKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }
        
        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (message === '') return;
            
            // Add user message
            addMessage(message, 'sent');
            
            // Clear input
            input.value = '';
            
            // Simulate response after delay
            setTimeout(() => {
                addBotResponse();
            }, 1000);
        }
        
        function addMessage(text, type) {
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message-bubble message-${type}`;
            
            if (type === 'sent') {
                messageDiv.innerHTML = `
                    <div>${text}</div>
                    <div class="text-xs text-blue-200 mt-1">Baru sahaja</div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="font-medium text-xs text-gray-500 mb-1">Pasukan Sokongan</div>
                    <div>${text}</div>
                    <div class="text-xs text-gray-400 mt-1">Baru sahaja</div>
                `;
            }
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function addBotResponse() {
            const responses = [
                "Terima kasih atas mesej anda. Pasukan sokongan kami akan membalas dalam masa terdekat.",
                "Kami telah menerima pertanyaan anda. Adakah terdapat maklumat tambahan yang boleh anda berikan?",
                "Untuk membantu anda dengan lebih baik, bolehkah anda nyatakan jenis masalah yang dihadapi?",
                "Mesej anda telah diterima. Kami akan menghubungi anda sebaik sahaja ada penyelesaian."
            ];
            
            const randomResponse = responses[Math.floor(Math.random() * responses.length)];
            addMessage(randomResponse, 'received');
        }
    </script>
</body>
</html>
