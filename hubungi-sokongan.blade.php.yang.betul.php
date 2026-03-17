<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hubungi Sokongan - E-Masjid</title>

    <!-- Favicon -->
    <x-favicon />

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Emoji Mart CSS -->
    <link href="https://cdn.jsdelivr.net/npm/emoji-mart@latest/css/emoji-mart.css" rel="stylesheet">

    <style>
        /* Chat Interface Styles */
        .chat-container {
            height: 500px;
            max-height: 500px;
        }
        
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }
        
        .message-bubble {
            max-width: 70%;
            word-wrap: break-word;
        }
        
        .message-sent {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: white;
            margin-left: auto;
            border-radius: 18px 18px 4px 18px;
        }
        
        .message-received {
            background: #F3F4F6;
            color: #374151;
            margin-right: auto;
            border-radius: 18px 18px 18px 4px;
        }
        
        .online-indicator {
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        .offline-indicator {
            width: 8px;
            height: 8px;
            background: #EF4444;
            border-radius: 50%;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        
        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: #F3F4F6;
            border-radius: 18px;
            margin-right: auto;
            max-width: 70px;
        }
        
        .typing-dot {
            width: 4px;
            height: 4px;
            background: #9CA3AF;
            border-radius: 50%;
            margin: 0 1px;
            animation: typing 1.4s infinite ease-in-out;
        }
        
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
        
        /* Custom Live Pulse Animation */
        .live-pulse {
            animation: livePulse 2s infinite ease-in-out;
        }
        
        @keyframes livePulse {
            0% { 
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
                opacity: 1;
            }
            50% { 
                transform: scale(1.1);
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
                opacity: 0.8;
            }
            100% { 
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
                opacity: 1;
            }
        }
        
        /* Enhanced heartbeat animation */
        .heartbeat {
            animation: heartbeat 1.5s infinite ease-in-out;
        }
        
        @keyframes heartbeat {
            0% { transform: scale(1); }
            14% { transform: scale(1.2); }
            28% { transform: scale(1); }
            42% { transform: scale(1.2); }
            70% { transform: scale(1); }
            100% { transform: scale(1); }
        }
        
        /* Custom Hover Effects with !important */
        .pelanggan-vip-hover:hover {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }
        
        .menunggu-maklumbalas-hover:hover {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }
        
        .permintaan-ciri-hover:hover {
            background-color: #f3e8ff !important;
            color: #6b21a8 !important;
        }
        
        /* Enhanced hover transitions */
        .nav-item-hover {
            transition: all 0.2s ease-in-out !important;
        }
        
        .nav-item-hover:hover {
            transform: translateX(4px) !important;
        }
        
        /* Professional gradient backgrounds */
        .gradient-bg-blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
        }
        
        .gradient-bg-green {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%) !important;
        }
        
        .gradient-bg-purple {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%) !important;
        }
        
        /* Enhanced content area styling */
        .content-item {
            border-left: 4px solid transparent !important;
            transition: all 0.3s ease-in-out !important;
            border-radius: 0 8px 8px 0 !important;
        }
        
        .content-item:hover {
            border-left-color: #3b82f6 !important;
            background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%) !important;
            transform: translateX(2px) !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15) !important;
        }
        
        /* Enhanced priority badges */
        .priority-badge {
            font-weight: 500 !important;
            font-size: 0.6875rem !important;
            padding: 3px 6px !important;
            border-radius: 4px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.025em !important;
            line-height: 1.2 !important;
            display: inline-block !important;
        }
        
        /* Status indicators */
        .status-online {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3) !important;
        }
        
        .status-typing {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3) !important;
        }
        
        /* Navigation enhancements */
        .nav-section-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 8px !important;
            margin-bottom: 8px !important;
        }
        
        /* Smooth scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Search input and icon specific styling */
        #searchInput {
            font-size: 0.875rem !important;
            padding: 8px 40px 8px 16px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            width: 256px !important;
            height: 40px !important;
            line-height: 1.5 !important;
        }
        
        #searchInput::placeholder {
            font-size: 0.8125rem !important;
            color: #9ca3af !important;
            font-weight: 400 !important;
            opacity: 1 !important;
        }
        
        #searchInput:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        
        /* Search icon specific positioning */
        .search-icon {
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 1.125rem !important;
            color: #9ca3af !important;
            pointer-events: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 18px !important;
            height: 18px !important;
        }
        
        /* Header action buttons specific styling */
        .header-action-btn {
            padding: 8px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 40px !important;
            height: 40px !important;
        }
        
        .header-action-btn .material-icons {
            font-size: 1.125rem !important;
            color: #9ca3af !important;
            transition: color 0.2s ease-in-out !important;
        }
        
        .header-action-btn:hover {
            background-color: #dbeafe !important;
        }
        
        .header-action-btn:hover .material-icons {
            color: #3b82f6 !important;
        }
        
        /* Mode toggle buttons specific styling */
        .mode-toggle-btn {
            display: inline-flex !important;
            align-items: center !important;
            padding: 8px 16px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            font-family: 'Figtree', 'Inter', sans-serif !important;
            border-radius: 8px !important;
            transition: all 0.2s ease-in-out !important;
            border: 1px solid transparent !important;
            line-height: 1.25rem !important;
            text-decoration: none !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
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
        
        .mode-toggle-btn.active {
            color: white !important;
            background-color: #3b82f6 !important;
            border-color: transparent !important;
        }
        
        .mode-toggle-btn.active:hover {
            background-color: #2563eb !important;
        }
        
        .mode-toggle-btn.inactive {
            color: #374151 !important;
            background-color: white !important;
            border-color: #d1d5db !important;
        }
        
        .mode-toggle-btn.inactive:hover {
            background-color: #f9fafb !important;
            border-color: #9ca3af !important;
        }
        
        /* Ensure consistent font sizing across all states */
        .mode-toggle-btn:hover,
        .mode-toggle-btn:focus,
        .mode-toggle-btn:active {
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            font-family: 'Figtree', 'Inter', sans-serif !important;
        }
        
        /* Navigation sidebar icons specific styling */
        .nav-sidebar .material-icons {
            font-size: 1.125rem !important;
            width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        /* Content area avatar icons */
        .content-avatar .material-icons {
            font-size: 1.125rem !important;
            width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        /* Header main icon */
        .header-main-icon .material-icons {
            font-size: 1.25rem !important;
            color: white !important;
        }
        
        /* Priority notification badges on avatars */
        .priority-notification {
            position: absolute !important;
            top: -4px !important;
            right: -4px !important;
            width: 16px !important;
            height: 16px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.625rem !important;
            font-weight: 700 !important;
            color: white !important;
            border: 2px solid white !important;
        }
        
        
        /* Chat Header */
        .chat-header {
            padding: 20px 24px !important;
            border-bottom: 1px solid #e5e7eb !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 16px 16px 0 0 !important;
        }
        
        .chat-user-info {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
        }
        
        .chat-user-avatar {
            width: 48px !important;
            height: 48px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
        }
        
        .chat-user-avatar .material-icons {
            color: white !important;
            font-size: 1.5rem !important;
        }
        
        .chat-user-details h3 {
            font-size: 1.125rem !important;
            font-weight: 600 !important;
            color: #1f2937 !important;
            margin: 0 !important;
            font-family: 'Poppins', sans-serif !important;
        }
        
        .chat-user-status {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            margin-top: 4px !important;
        }
        
        .chat-status-dot {
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            background: #10b981 !important;
            animation: livePulse 2s infinite ease-in-out !important;
        }
        
        .chat-status-text {
            font-size: 0.875rem !important;
            color: #10b981 !important;
            font-weight: 500 !important;
        }
        
        .chat-priority-badge {
            background: #fef2f2 !important;
            color: #dc2626 !important;
            padding: 4px 8px !important;
            border-radius: 6px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
        
        /* Chat Close Button */
        .chat-close-btn {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            background: #f3f4f6 !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .chat-close-btn:hover {
            background: #e5e7eb !important;
            transform: scale(1.1) !important;
        }
        
        .chat-close-btn .material-icons {
            color: #6b7280 !important;
            font-size: 1.25rem !important;
        }
        
        /* Chat Messages Area */
        .chat-messages {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 20px 24px !important;
            background: #fafafa !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 16px !important;
        }
        
        .chat-messages::-webkit-scrollbar {
            width: 6px !important;
        }
        
        .chat-messages::-webkit-scrollbar-track {
            background: #f1f5f9 !important;
            border-radius: 3px !important;
        }
        
        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1 !important;
            border-radius: 3px !important;
        }
        
        /* Message Bubbles */
        .message-bubble {
            max-width: 70% !important;
            padding: 12px 16px !important;
            border-radius: 18px !important;
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
            word-wrap: break-word !important;
        }
        
        .message-received {
            background: white !important;
            color: #374151 !important;
            align-self: flex-start !important;
            border: 1px solid #e5e7eb !important;
            border-bottom-left-radius: 4px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }
        
        .message-sent {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            align-self: flex-end !important;
            border-bottom-right-radius: 4px !important;
            box-shadow: 0 1px 3px rgba(59, 130, 246, 0.3) !important;
        }
        
        .message-time {
            font-size: 0.75rem !important;
            opacity: 0.7 !important;
            margin-top: 4px !important;
            text-align: right !important;
        }
        
        /* Typing Indicator */
        .typing-indicator {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 1px !important;
            padding: 8px 10px !important;
            background: white !important;
            border-radius: 18px 18px 18px 4px !important;
            align-self: flex-start !important;
            border: 1px solid #e5e7eb !important;
            max-width: 35px !important;
            min-height: 30px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }

        /* Live Typing Animation */
        .typing-dot {
            width: 2px !important;
            height: 2px !important;
            background-color: #9ca3af !important;
            border-radius: 50% !important;
            animation: typingDots 1.4s infinite ease-in-out !important;
        }

        .typing-dot:nth-child(1) {
            animation-delay: -0.32s !important;
        }

        .typing-dot:nth-child(2) {
            animation-delay: -0.16s !important;
        }

        @keyframes typingDots {
            0%, 80%, 100% {
                transform: scale(0.8) !important;
                opacity: 0.5 !important;
            }
            40% {
                transform: scale(1.2) !important;
                opacity: 1 !important;
            }
        }
        
        .typing-dot {
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            background: #9ca3af !important;
            animation: typing 1.4s infinite ease-in-out !important;
        }
        
        .typing-dot:nth-child(1) { animation-delay: -0.32s !important; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s !important; }
        .typing-dot:nth-child(3) { animation-delay: 0s !important; }
        
        @keyframes typing {
            0%, 80%, 100% { 
                transform: scale(0.8) !important; 
                opacity: 0.5 !important; 
            }
            40% { 
                transform: scale(1) !important; 
                opacity: 1 !important; 
            }
        }
        
        /* Chat Input Area */
        .chat-input-area {
            padding: 20px 24px !important;
            border-top: 1px solid #e5e7eb !important;
            background: white !important;
            border-radius: 0 0 16px 16px !important;
        }
        
        .chat-input-container {
            display: flex !important;
            align-items: flex-end !important;
            gap: 12px !important;
        }
        
        .chat-input-wrapper {
            flex: 1 !important;
            position: relative !important;
        }
        
        .chat-input {
            width: 100% !important;
            min-height: 44px !important;
            max-height: 120px !important;
            padding: 12px 50px 12px 16px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 22px !important;
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
            resize: none !important;
            outline: none !important;
            transition: border-color 0.2s ease-in-out !important;
            font-family: 'Figtree', 'Inter', sans-serif !important;
        }
        
        .chat-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        
        .chat-input::placeholder {
            color: #9ca3af !important;
        }
        
        .chat-attachment-btn {
            position: absolute !important;
            right: 12px !important;
            bottom: 10px !important;
            width: 32px !important;
            height: 32px !important;
            border: none !important;
            background: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
            transition: background-color 0.2s ease-in-out !important;
        }
        
        .chat-attachment-btn:hover {
            background: #f3f4f6 !important;
        }
        
        .chat-attachment-btn .material-icons {
            color: #6b7280 !important;
            font-size: 1.25rem !important;
        }
        
        .chat-send-btn {
            width: 44px !important;
            height: 44px !important;
            border: none !important;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            border-radius: 50% !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
        }
        
        .chat-send-btn:hover {
            transform: scale(1.05) !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
        }
        
        .chat-send-btn:disabled {
            background: #d1d5db !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .chat-send-btn .material-icons {
            font-size: 1.25rem !important;
        }

        /* Chat Input Area Styles */
        /* Chat Attachment Button - Perfect Middle Center Alignment */
        .chat-input-container .chat-attach-btn {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 36px !important;
            height: 36px !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 8px !important;
            color: #9ca3af !important;
            cursor: pointer !important;
            transition: all 0.2s ease-in-out !important;
            flex-shrink: 0 !important;
            align-self: center !important;
        }

        .chat-input-container .chat-attach-btn:hover {
            background: #f3f4f6 !important;
            color: #6b7280 !important;
            transform: scale(1.05) !important;
        }

        /* Chat Attachment Icon - Separated CSS for Better Specificity */
        .chat-input-container .chat-attach-btn .material-icons {
            font-size: 22px !important;
            line-height: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 18px !important;
            height: 18px !important;
            font-family: 'Material Icons' !important;
            font-weight: normal !important;
            font-style: normal !important;
            text-align: center !important;
            vertical-align: middle !important;
        }

        .chat-input-container .chat-attach-icon {
            font-size: 20px !important;
            line-height: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 20px !important;
            height: 20px !important;
            font-family: 'Material Icons' !important;
            font-weight: normal !important;
            font-style: normal !important;
            text-align: center !important;
            vertical-align: middle !important;
        }

        .chat-textarea-input {
            width: 100% !important;
            min-width: 350px !important;
            resize: none !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            line-height: 1.4 !important;
            background: white !important;
            color: #1f2937 !important;
            outline: none !important;
            transition: all 0.2s ease-in-out !important;
            max-height: 120px !important;
            min-height: 36px !important;
        }

        /* Chat Textarea Input - Perfect Middle Alignment Fix */
        .chat-input-container .chat-textarea-input {
            width: 100% !important;
            min-width: 350px !important;
            resize: none !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            line-height: 1.4 !important;
            background: white !important;
            color: #1f2937 !important;
            outline: none !important;
            min-height: 36px !important;
            max-height: 120px !important;
            align-self: center !important;
            vertical-align: middle !important;
            box-sizing: border-box !important;
            margin: 0 !important;
        }

        /* Chat Input Flex Container - Perfect Alignment */
        .chat-input-container .flex.items-center {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            min-height: 36px !important;
            gap: 8px !important;
            width: 100% !important;
        }

        /* Chat Input Flex-1 Container - Middle Alignment */
        .chat-input-container .flex-1 {
            display: flex !important;
            align-items: center !important;
            flex: 1 !important;
            min-height: 36px !important;
            position: relative !important;
        }

        .chat-textarea-input::placeholder {
            font-size: 13px !important;
            color: #9ca3af !important;
        }

        .chat-textarea-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
            outline: none !important;
        }

        /* Chat Send Button - Perfect Middle Center Alignment */
        .chat-input-container .chat-send-btn {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 36px !important;
            height: 36px !important;
            padding: 0 !important;
            background: #3b82f6 !important;
            border: none !important;
            border-radius: 8px !important;
            color: white !important;
            cursor: pointer !important;
            transition: all 0.2s ease-in-out !important;
            flex-shrink: 0 !important;
            align-self: center !important;
            margin: 0 !important;
        }

        .chat-input-container .chat-send-btn:hover:not(:disabled) {
            background: #2563eb !important;
            transform: scale(1.05) !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
            opacity: 1 !important;
        }

        .chat-input-container .chat-send-btn:disabled {
            background: #d1d5db !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
            opacity: 0.6 !important;
            color: #9ca3af !important;
        }

        .chat-send-icon {
            font-size: 16px !important;
            line-height: 1 !important;
            display: block !important;
            pointer-events: none !important;
        }

        /* Chat Send Icon - Perfect Center Alignment */
        .chat-input-container .chat-send-btn .material-icons,
        .chat-input-container .chat-send-icon {
            font-size: 16px !important;
            line-height: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 16px !important;
            height: 16px !important;
            margin: 0 auto !important;
            text-align: center !important;
            vertical-align: middle !important;
            pointer-events: none !important;
        }

        /* Emoji Button - Clickable Smiley Face - Default State */
        .chat-emoji-btn {
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: transparent !important;
            border: none !important;
            color: #9ca3af !important;
            font-size: 28px !important;
            cursor: pointer !important;
            z-index: 2 !important;
            width: 28px !important;
            height: 28px !important;
            padding: 0 !important;
            border-radius: 50% !important;
            transition: all 0.2s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Emoji Button - Hover State - Perfect Circle */
        .chat-emoji-btn:hover {
            background: #f3f4f6 !important;
            color: #6b7280 !important;
            transform: translateY(-50%) scale(1.1) !important;
            width: 28px !important;
            height: 28px !important;
            border-radius: 50% !important;
        }

        /* Emoji Mart Picker Container */
        .emoji-picker-container {
            position: absolute !important;
            bottom: 50px !important;
            right: 0 !important;
            z-index: 1000 !important;
            display: none !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden !important;
        }

        .emoji-picker-container.show {
            display: block !important;
            animation: emojiPickerSlideUp 0.2s ease-out !important;
        }

        @keyframes emojiPickerSlideUp {
            from {
                opacity: 0 !important;
                transform: translateY(10px) !important;
            }
            to {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        }

        .emoji-picker-container em-emoji-picker {
            --border-radius: 12px !important;
            --font-family: 'Poppins', sans-serif !important;
            --font-size: 14px !important;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            --background: #ffffff !important;
            --color-border: #e5e7eb !important;
            --color-border-over: #d1d5db !important;
            width: 320px !important;
            height: 350px !important;
        }

        /* Chat Messages Container - No Padding/Margin */
        .chat-messages-container {
            flex: 1 !important;
            overflow-y: auto !important;
            background: #f9fafb !important;
            padding: 0 !important;
            margin: 0 !important;
            min-height: 0 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        /* Chat Message Items */
        .chat-message-item {
            padding: 12px 16px !important;
            margin: 0 !important;
            border-bottom: 1px solid #f3f4f6 !important;
            display: flex !important;
        }

        .chat-message-item:last-child {
            border-bottom: none !important;
        }

        /* Message Bubbles */
        .chat-message-received {
            justify-content: flex-start !important;
        }

        .chat-message-sent {
            justify-content: flex-end !important;
        }

        .chat-bubble {
            max-width: 70% !important;
            padding: 12px 16px !important;
            border-radius: 12px !important;
            margin: 0 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
        }

        .chat-bubble-received {
            background: white !important;
            color: #1f2937 !important;
            border-bottom-left-radius: 4px !important;
        }

        .chat-bubble-sent {
            background: #3b82f6 !important;
            color: white !important;
            border-bottom-right-radius: 4px !important;
        }

        .chat-message-text {
            font-size: 12px !important;
            line-height: 1.4 !important;
            margin: 0 !important;
        }

        .chat-message-time {
            font-size: 10px !important;
            margin-top: 4px !important;
            opacity: 0.7 !important;
        }

        .chat-message-time-received {
            color: #6b7280 !important;
        }

        .chat-message-time-sent {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* Chat Header - No Side Padding */
        .chat-header-container {
            padding: 0 !important;
            margin: 0 !important;
            border-bottom: 1px solid #e5e7eb !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            flex-shrink: 0 !important;
        }

        .chat-header-content {
            display: flex !important;
            align-items: center !important;
            padding: 8px 16px !important;
            gap: 10px !important;
        }

        .chat-back-btn {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 28px !important;
            height: 28px !important;
            padding: 0 !important;
            background: rgba(255, 255, 255, 0.8) !important;
            border: none !important;
            border-radius: 6px !important;
            color: #6b7280 !important;
            cursor: pointer !important;
            transition: all 0.2s ease-in-out !important;
        }

        .chat-menu-btn {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 28px !important;
            height: 28px !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 6px !important;
            color: #6b7280 !important;
            cursor: pointer !important;
            transition: all 0.2s ease-in-out !important;
            margin-left: auto !important;
        }

        .chat-back-btn:hover {
            background: white !important;
            color: #374151 !important;
            transform: translateX(-1px) !important;
        }

        .chat-menu-btn:hover {
            background: rgba(255, 255, 255, 0.8) !important;
            color: #374151 !important;
            transform: scale(1.1) !important;
        }

        .chat-back-btn .material-icons {
            font-size: 16px !important;
        }

        .chat-menu-btn .material-icons {
            font-size: 16px !important;
        }

        /* Chat Menu Container */
        .chat-menu-btn:parent,
        .relative:has(.chat-menu-btn) {
            position: relative !important;
        }

        /* Chat Dropdown Menu */
        #chatDropdownMenu.chat-dropdown-menu {
            position: absolute !important;
            top: calc(100% + 4px) !important;
            right: 0 !important;
            left: auto !important;
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            min-width: 220px !important;
            max-width: 280px !important;
            z-index: 9999 !important;
            margin: 0 !important;
            overflow: hidden !important;
            transform: none !important;
        }

        .chat-menu-item {
            display: flex !important;
            align-items: center !important;
            padding: 12px 16px !important;
            cursor: pointer !important;
            transition: background-color 0.15s ease !important;
            font-size: 14px !important;
            color: #374151 !important;
            gap: 12px !important;
        }

        .chat-menu-item:hover {
            background-color: #f3f4f6 !important;
        }

        .chat-menu-item.text-red-600 {
            color: #dc2626 !important;
        }

        .chat-menu-item.text-red-600:hover {
            background-color: #fef2f2 !important;
        }

        .chat-menu-item .material-icons {
            font-size: 18px !important;
            color: #6b7280 !important;
        }

        .chat-menu-item.text-red-600 .material-icons {
            color: #dc2626 !important;
        }

        .chat-menu-separator {
            height: 1px !important;
            background-color: #e5e7eb !important;
            margin: 4px 0 !important;
        }

        .chat-user-info-section {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
        }

        .chat-user-avatar-container {
            width: 36px !important;
            height: 36px !important;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
        }

        .chat-user-avatar-container .material-icons {
            color: white !important;
            font-size: 18px !important;
        }

        .chat-user-details-section {
            flex: 1 !important;
        }

        .chat-user-name {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #1f2937 !important;
            margin: 0 8px 2px 0 !important;
            font-family: 'Poppins', sans-serif !important;
        }

        /* Chat User Name and Priority Badge Container */
        .flex.items-center.space-x-3 {
            gap: 8px !important;
        }

        /* Specific spacing for chat priority badge */
        #chatPriorityBadge {
            margin-left: 8px !important;
        }

        .chat-user-status-section {
            display: flex !important;
            align-items: center !important;
            gap: 5px !important;
        }

        .chat-status-dot {
            width: 5px !important;
            height: 5px !important;
            background: #10b981 !important;
            border-radius: 50% !important;
            animation: pulse 2s infinite !important;
        }

        .chat-status-text {
            font-size: 11px !important;
            color: #10b981 !important;
            font-weight: 500 !important;
        }

        .chat-priority-badge {
            font-size: 10px !important;
            padding: 1px 5px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            background: #fef2f2 !important;
            color: #dc2626 !important;
        }

        /* Main Content Container - No Padding */
        .main-content-container {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            min-height: 0 !important;
            background: white !important;
            position: relative !important;
        }

        /* Chat Interface Content - No Padding */
        #chatInterface {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Ticket Interface Content - No Padding */
        #ticketInterface {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Content Items - Controlled Padding with Aligned Separators */
        .content-item {
            padding: 16px !important;
            margin: 0 !important;
            border-bottom: 1px solid #e5e7eb !important;
            position: relative !important;
        }

        .content-item:hover {
            background: #f9fafb !important;
        }

        /* Extend separator lines to align with navigation border */
        .content-item::after {
            content: '' !important;
            position: absolute !important;
            bottom: -1px !important;
            left: -1px !important;
            right: 0 !important;
            height: 1px !important;
            background: #e5e7eb !important;
        }

        /* Inline Chat Interface - Absolute Positioning */
        #inlineChatInterface {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: white !important;
            z-index: 10 !important;
            display: flex !important;
            flex-direction: column !important;
        }

        #inlineChatInterface.hidden {
            display: none !important;
        }

        /* Hide Content Header when Chat is Active - Parent Container Approach */
        .chat-mode .content-header {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
            opacity: 0 !important;
        }

        /* Backup selectors for safety */
        .flex-1.chat-mode .content-header,
        [class*="chat-mode"] .content-header {
            display: none !important;
        }

        /* Chat Input Area - No Side Padding */
        .chat-input-container {
            padding: 8px 16px 8px 4px !important;
            margin: 0 !important;
            border-top: 1px solid #e5e7eb !important;
            background: white !important;
            width: 100% !important;
        }

        /* Chat Interface - Wider Content Area */
        #inlineChatInterface {
            width: 100% !important;
            max-width: none !important;
        }

        /* Chat Messages Area - Full Width */
        #chatMessages {
            width: 100% !important;
            max-width: none !important;
        }

        /* Chat Input Flex Container - Wider */
        .chat-input-container .flex {
            width: 100% !important;
            min-width: 400px !important;
        }

        /* Right Content Area - Ensure Full Width Usage */
        .flex-1.bg-white {
            min-width: 0 !important;
            flex: 1 1 auto !important;
        }

        /* Content Header - Align border with navigation */
        .content-header {
            position: relative !important;
        }

        .content-header::after {
            content: '' !important;
            position: absolute !important;
            bottom: -1px !important;
            left: -1px !important;
            right: 0 !important;
            height: 1px !important;
            background: #e5e7eb !important;
        }

        /* Navigation sidebar - ensure border extends properly */
        .nav-sidebar {
            position: relative !important;
        }

    </style>
</head>

<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate">
    <x-double-navbar :user="auth()->user()" />

    <div class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Dashboard Container - Same as Documents -->
            <div class="bg-white shadow-lg border-x border-gray-200">
                <!-- Header Section - Same Style as Documents -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <span class="material-icons text-2xl text-purple-600">support_agent</span>
                                <h1 class="text-xl font-semibold text-gray-900">Sokongan Sistem</h1>
                            </div>
                        </div>

                        <!-- Mode Toggle Buttons -->
                        <div class="flex items-center space-x-3">
                            <button id="chatModeBtn" class="mode-toggle-btn active">
                                <span class="material-icons">chat</span>
                                Chat Langsung
                            </button>
                            <button id="ticketModeBtn" class="mode-toggle-btn inactive">
                                <span class="material-icons">confirmation_number</span>
                                Sistem Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area with Sidebar -->
                <div class="flex">
                    <!-- Left Sidebar Navigation -->
                    <div class="w-56 bg-white border-r border-gray-200 flex flex-col nav-sidebar" style="height: calc(100vh - 180px) !important;">
                        <!-- Navigation Header -->
                        <div class="p-5 flex-shrink-0 !important">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">NAVIGASI</h3>
                        </div>

                        <!-- Scrollable Navigation Content -->
                        <div class="flex-1 overflow-y-auto" style="min-height: 0 !important;">
                            <!-- Chat Navigation (Default Active) -->
                            <div id="chatNavigation" class="p-4">
                            <!-- Main Header -->
                            <div class="mb-4">
                                <div class="flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg cursor-pointer" style="font-family: 'Poppins', sans-serif !important;">
                                    <span class="material-icons text-lg mr-3">business</span>
                                    Operasi Sokongan
                                </div>
                            </div>

                            <!-- OPERASI AKTIF Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Operasi Aktif
                                    </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">flash_on</span>
                                        Sesi Langsung
                                        <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">3</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-orange-700 hover:bg-orange-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">schedule</span>
                                        Senarai Tunggu
                                        <span class="ml-auto text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">8</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">sync</span>
                                        Dalam Proses
                                        <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">5</span>
                                    </a>
                                </div>
                                </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- AKSES KHAS Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Akses Khas
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-yellow-600 rounded-lg transition-colors group pelanggan-vip-hover nav-item-hover">
                                        <span class="material-icons text-lg mr-3">star</span>
                                        Pelanggan VIP
                                        <span class="ml-auto text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">2</span>
                                    </a>
                            </div>
                        </div>
                        
                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- ANALISIS & LAPORAN Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Analisis & Laporan
                                    </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">dashboard</span>
                                        Ringkasan Hari Ini
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">trending_up</span>
                                        Metrik Prestasi
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">history</span>
                                        Sejarah Sesi
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ticket Navigation (Hidden by default) -->
                        <div id="ticketNavigation" class="p-4 hidden">
                            <!-- Main Header -->
                            <div class="mb-4">
                                <div class="flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg cursor-pointer" style="font-family: 'Poppins', sans-serif !important;">
                                    <span class="material-icons text-lg mr-3">confirmation_number</span>
                                    Sistem Tiket
                                </div>
                                </div>

                            <!-- TAHAP KEUTAMAAN Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tahap Keutamaan
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-red-700 hover:bg-red-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-red-600">priority_high</span>
                                        Kecemasan
                                        <span class="ml-auto text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">2</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-orange-700 hover:bg-orange-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-orange-600">warning</span>
                                        Tinggi
                                        <span class="ml-auto text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">5</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-blue-600">info</span>
                                        Sederhana
                                        <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">8</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3 text-gray-500">low_priority</span>
                                        Rendah
                                        <span class="ml-auto text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">15</span>
                                    </a>
                            </div>
                        </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- STATUS TIKET Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status Tiket
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">fiber_new</span>
                                        Tiket Baru
                                        <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">6</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">work</span>
                                        Dalam Kerja
                                        <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">12</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-yellow-700 rounded-lg transition-colors group menunggu-maklumbalas-hover nav-item-hover">
                                        <span class="material-icons text-lg mr-3">pause_circle</span>
                                        Menunggu Maklumbalas
                                        <span class="ml-auto text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">4</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">check_circle</span>
                                        Selesai
                                        <span class="ml-auto text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">25</span>
                                    </a>
                    </div>
                        </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- KATEGORI MASALAH Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Kategori Masalah
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">bug_report</span>
                                        Teknikal
                                        <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">18</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded-lg transition-colors group">
                                        <span class="material-icons text-lg mr-3">account_circle</span>
                                        Akaun
                                        <span class="ml-auto text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">8</span>
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-purple-700 rounded-lg transition-colors group permintaan-ciri-hover nav-item-hover">
                                        <span class="material-icons text-lg mr-3">star</span>
                                        Permintaan Ciri
                                        <span class="ml-auto text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">4</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Separator -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- LAPORAN & ANALISIS Group -->
                            <div class="mb-4">
                                <div class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Laporan & Analisis
                                </div>
                                <div class="mt-2 space-y-1">
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">dashboard</span>
                                        Ringkasan Tiket
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">assessment</span>
                                        Laporan SLA
                                    </a>
                                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <span class="material-icons text-lg mr-3">trending_up</span>
                                        Analisis Trend
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Right Content Area -->
                    <div class="flex-1 bg-white flex flex-col" style="height: calc(100vh - 180px) !important;">
                        <!-- Content Header -->
                        <div class="content-header px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-white to-gray-50 flex-shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg header-main-icon">
                                        <span class="material-icons">support_agent</span>
                                    </div>
                                    <div>
                                        <h2 id="contentTitle" class="text-lg font-semibold text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Pengguna Dalam Talian</h2>
                                        <p id="contentSubtitle" class="text-sm text-gray-600 flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                            3 pengguna dalam talian menunggu sokongan
                                        </p>
                                        </div>
                                    </div>
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <input type="text" placeholder="Cari pengguna..." id="searchInput">
                                        <span class="material-icons search-icon">search</span>
                                </div>
                                    <button class="header-action-btn" title="Filter">
                                        <span class="material-icons">filter_list</span>
                                    </button>
                                    <button class="header-action-btn" title="Refresh">
                                        <span class="material-icons">refresh</span>
                                    </button>
                                    <button class="header-action-btn" title="Menu">
                                    <span class="material-icons">more_vert</span>
                                </button>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content List with Horizontal Separators -->
                        <div class="main-content-container" id="mainContentArea">
                            <!-- Chat Interface Content -->
                            <div id="chatInterface" class="space-y-0">
                                <!-- User Item 1 -->
                                <div class="flex items-center cursor-pointer content-item" onclick="openChat('Ahmad Razak', 'Kecemasan', 'Menunggu sokongan untuk masalah upload dokumen...', 'U9M9HU', '192.168.1.100', 'Kuala Lumpur', 'VIP User')">
                                    <div class="relative mr-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-md content-avatar">
                                            <span class="material-icons text-white">person</span>
                                </div>
                                        <div class="priority-notification bg-red-500">
                                            <span>!</span>
                                </div>
                            </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Ahmad Razak</h3>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full live-pulse" style="animation: livePulse 2s infinite ease-in-out !important;"></div>
                                                <span class="text-xs text-green-600 font-medium">Online</span>
                                </div>
                                </div>
                                        <p class="text-xs text-gray-600 mt-1">Menunggu sokongan untuk masalah upload dokumen...</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">2 minit yang lalu</span>
                                            <span class="bg-red-100 text-red-800 priority-badge">Kecemasan</span>
                            </div>
                                </div>
                            </div>

                                <!-- User Item 2 -->
                                <div class="flex items-center cursor-pointer content-item">
                                    <div class="relative mr-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-md content-avatar">
                                            <span class="material-icons text-white">person</span>
                                        </div>
                                        <div class="priority-notification bg-blue-500">
                                            <span>?</span>
                                        </div>
                                    </div>
                                <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Siti Aminah</h3>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full live-pulse" style="animation: livePulse 2s infinite ease-in-out !important;"></div>
                                                <span class="text-xs text-green-600 font-medium">Online</span>
                                </div>
                            </div>
                                        <p class="text-xs text-gray-600 mt-1">Pertanyaan tentang sistem pengurusan kariah...</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">5 minit yang lalu</span>
                                            <span class="bg-blue-100 text-blue-800 priority-badge">Sederhana</span>
                                        </div>
                            </div>
                        </div>

                                <!-- User Item 3 -->
                                <div class="flex items-center cursor-pointer content-item">
                                    <div class="relative mr-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center shadow-md content-avatar">
                                            <span class="material-icons text-white">person</span>
                                        </div>
                                        <div class="priority-notification bg-orange-500">
                                            <span>⚠</span>
                                        </div>
                                    </div>
                                <div class="flex-1">
                                <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">Mohd Hafiz</h3>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-2 h-2 bg-green-500 rounded-full live-pulse" style="animation: livePulse 2s infinite ease-in-out !important;"></div>
                                                <span class="text-xs text-green-600 font-medium">Online</span>
                                </div>
                                    </div>
                                        <p class="text-xs text-gray-600 mt-1">Masalah dengan sistem pembayaran...</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">8 minit yang lalu</span>
                                            <span class="bg-orange-100 text-orange-800 priority-badge">Tinggi</span>
                                    </div>
                                    </div>
                                    </div>
                                    </div>

                            <!-- Inline Chat Interface (Hidden by default) -->
                            <div id="inlineChatInterface" class="hidden flex flex-col h-full">
                                <!-- Chat Header -->
                                <div class="chat-header-container">
                                    <div class="chat-header-content">
                                        <button onclick="closeChat()" class="chat-back-btn" title="Kembali">
                                            <span class="material-icons">arrow_back</span>
                                </button>
                                        <div class="chat-user-info-section">
                                            <div class="chat-user-avatar-container">
                                                <span class="material-icons">person</span>
                            </div>
                                            <div class="chat-user-details-section">
                                                <div class="flex items-center space-x-3">
                                                    <h3 id="chatUserName" class="chat-user-name">Ahmad Razak</h3>
                                                    <span id="chatPriorityBadge" class="chat-priority-badge">Kecemasan</span>
                                                </div>
                                                <div class="chat-user-status-section">
                                                    <div class="chat-status-dot"></div>
                                                    <span class="chat-status-text">Dalam Talian • Masjid Id : U9M9HU • IP Address : 192.168.1.100 • Lokasi : Kuala Lumpur • Status : Normal User</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <button class="chat-menu-btn" title="Menu" onclick="toggleChatMenu()">
                                                <span class="material-icons">more_vert</span>
                                            </button>
                                            
                                            <!-- Dropdown Menu -->
                                            <div id="chatDropdownMenu" class="chat-dropdown-menu hidden">
                                                <div class="chat-menu-item" onclick="toggleVipStatus()">
                                                    <span class="material-icons">star</span>
                                                    <span>Tukar Status VIP</span>
                                                </div>
                                                <div class="chat-menu-item" onclick="setPriority()">
                                                    <span class="material-icons">flag</span>
                                                    <span>Tandakan sebagai Prioriti</span>
                                                </div>
                                                <div class="chat-menu-item" onclick="transferChat()">
                                                    <span class="material-icons">swap_horiz</span>
                                                    <span>Transfer ke Agent Lain</span>
                                                </div>
                                                <div class="chat-menu-item" onclick="escalateChat()">
                                                    <span class="material-icons">trending_up</span>
                                                    <span>Escalate ke Supervisor</span>
                                                </div>
                                                <div class="chat-menu-separator"></div>
                                                <div class="chat-menu-item" onclick="viewUserProfile()">
                                                    <span class="material-icons">person</span>
                                                    <span>Lihat Profil Pengguna</span>
                                                </div>
                                                <div class="chat-menu-item" onclick="viewChatHistory()">
                                                    <span class="material-icons">history</span>
                                                    <span>Sejarah Chat</span>
                                                </div>
                                                <div class="chat-menu-item" onclick="exportChatLog()">
                                                    <span class="material-icons">download</span>
                                                    <span>Export Chat Log</span>
                                                </div>
                                                <div class="chat-menu-separator"></div>
                                                <div class="chat-menu-item text-red-600" onclick="endChat()">
                                                    <span class="material-icons">close</span>
                                                    <span>Tamatkan Sembang</span>
                                                </div>
                                            </div>
                                        </div>
                        </div>
                    </div>

                                <!-- Chat Messages Area -->
                                <div class="chat-messages-container" id="chatMessages">
                                    <!-- Sample Messages -->
                                    <div class="chat-message-item chat-message-received">
                                        <div class="chat-bubble chat-bubble-received">
                                            <p class="chat-message-text">Assalamualaikum, saya ada masalah dengan sistem upload dokumen. Bila saya cuba upload fail PDF, ia menunjukkan error "File too large" walaupun saiz fail hanya 2MB sahaja.</p>
                                            <p class="chat-message-time chat-message-time-received">2 minit yang lalu</p>
                                        </div>
                                    </div>
                                    
                                    <div class="chat-message-item chat-message-sent">
                                        <div class="chat-bubble chat-bubble-sent">
                                            <p class="chat-message-text">Waalaikumussalam Ahmad. Terima kasih kerana menghubungi sokongan teknikal. Saya akan bantu anda menyelesaikan masalah ini. Boleh beritahu saya jenis browser yang anda gunakan?</p>
                                            <p class="chat-message-time chat-message-time-sent">1 minit yang lalu</p>
                                </div>
                            </div>

                                    <div class="chat-message-item chat-message-received">
                                        <div class="chat-bubble chat-bubble-received">
                                            <p class="chat-message-text">Saya guna Google Chrome versi terkini. Masalah ini baru mula berlaku hari ini.</p>
                                            <p class="chat-message-time chat-message-time-received">30 saat yang lalu</p>
                                        </div>
                                    </div>

                                    <!-- Add more sample messages to test scrolling -->
                                    <div class="chat-message-item chat-message-sent">
                                        <div class="chat-bubble chat-bubble-sent">
                                            <p class="chat-message-text">Baik, saya akan cuba beberapa langkah penyelesaian. Boleh anda cuba clear cache browser anda dahulu?</p>
                                            <p class="chat-message-time chat-message-time-sent">Baru sahaja</p>
                                        </div>
                                    </div>

                                    <div class="chat-message-item chat-message-received">
                                        <div class="chat-bubble chat-bubble-received">
                                            <p class="chat-message-text">Okay, saya cuba dulu. Macam mana nak clear cache?</p>
                                            <p class="chat-message-time chat-message-time-received">Baru sahaja</p>
                                        </div>
                                    </div>

                                    <div class="chat-message-item chat-message-sent">
                                        <div class="chat-bubble chat-bubble-sent">
                                            <p class="chat-message-text">Untuk Chrome: 1) Tekan Ctrl+Shift+Delete 2) Pilih "Cached images and files" 3) Klik "Clear data"</p>
                                            <p class="chat-message-time chat-message-time-sent">Baru sahaja</p>
                                        </div>
                                    </div>

                                    <!-- Typing Indicator -->
                                    <div id="typingIndicator" class="chat-message-item chat-message-received hidden">
                                        <div class="chat-bubble chat-bubble-received typing-indicator">
                                            <div class="typing-dot"></div>
                                            <div class="typing-dot"></div>
                                            <div class="typing-dot"></div>
                                        </div>
                                    </div>

                                <!-- Chat Input Area -->
                                <div class="chat-input-container flex-shrink-0">
                                    <div class="flex items-center" style="min-height: 36px !important; gap: 8px !important;">
                                        <button class="chat-attach-btn" title="Lampirkan fail">
                                            <span class="material-icons chat-attach-icon">attach_file</span>
                                        </button>
                                        <div class="flex-1">
                                            <textarea id="chatInput" rows="1" placeholder="Taip mesej anda..." class="chat-textarea-input"></textarea>
                                            <button class="chat-emoji-btn" onclick="toggleEmojiPicker()" title="Pilih emoji">☺</button>
                                            
                                            <!-- Emoji Mart Picker Container -->
                                            <div id="emojiPicker" class="emoji-picker-container">
                                                <!-- Emoji Mart will be rendered here -->
                                    </div>
                                    </div>
                                        <button id="chatSendBtn" onclick="sendMessage()" class="chat-send-btn" disabled>
                                            <span class="material-icons chat-send-icon">send</span>
                                        </button>
                                    </div>
                            </div>
                        </div>

                            <!-- Ticket Interface Content (Hidden by default) -->
                            <div id="ticketInterface" class="space-y-0 hidden">
                                <!-- Urgent Ticket -->
                                <div class="flex items-center cursor-pointer content-item">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <span class="material-icons text-red-600">priority_high</span>
                            </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">#TKT-001 - Sistem tidak boleh login</h3>
                                            <span class="bg-red-100 text-red-800 priority-badge">Kecemasan</span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">Semua pengguna tidak dapat login ke sistem...</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">Ahmad Razak • 30 minit yang lalu</span>
                                            <span class="text-xs text-red-600 font-medium">Belum dijawab</span>
                                    </div>
                                    </div>
                                </div>

                                <!-- High Priority Ticket -->
                                <div class="flex items-center cursor-pointer content-item">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                                        <span class="material-icons text-orange-600">warning</span>
                                </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">#TKT-002 - Upload dokumen gagal</h3>
                                            <span class="bg-orange-100 text-orange-800 priority-badge">Tinggi</span>
                            </div>
                                        <p class="text-xs text-gray-600 mt-1">Tidak dapat upload fail PDF lebih dari 5MB...</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">Siti Aminah • 1 jam yang lalu</span>
                                            <span class="text-xs text-blue-600 font-medium">Dalam proses</span>
                                        </div>
                                        </div>
                                    </div>

                                <!-- Medium Priority Ticket -->
                                <div class="flex items-center cursor-pointer content-item">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <span class="material-icons text-blue-600">info</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-medium text-gray-900" style="font-family: 'Poppins', sans-serif !important;">#TKT-003 - Permintaan ciri baru</h3>
                                            <span class="bg-blue-100 text-blue-800 priority-badge">Sederhana</span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">Boleh tambah fungsi export laporan ke Excel?...</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">Mohd Hafiz • 2 jam yang lalu</span>
                                            <span class="text-xs text-green-600 font-medium">Selesai</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                        </div>
                    </div>


    <!-- Updated JavaScript for Documents-Style Layout -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const chatModeBtn = document.getElementById('chatModeBtn');
                            const ticketModeBtn = document.getElementById('ticketModeBtn');
                            const chatInterface = document.getElementById('chatInterface');
                            const ticketInterface = document.getElementById('ticketInterface');
            const chatNavigation = document.getElementById('chatNavigation');
            const ticketNavigation = document.getElementById('ticketNavigation');
            const contentTitle = document.getElementById('contentTitle');
            const contentSubtitle = document.getElementById('contentSubtitle');

            // Chat Mode Button Click
                            chatModeBtn.addEventListener('click', function() {
                // Update button states
                chatModeBtn.classList.remove('inactive');
                chatModeBtn.classList.add('active');
                
                ticketModeBtn.classList.remove('active');
                ticketModeBtn.classList.add('inactive');

                // Show/hide content interfaces
                                chatInterface.classList.remove('hidden');
                                ticketInterface.classList.add('hidden');
                
                // Show/hide navigation sections
                chatNavigation.classList.remove('hidden');
                ticketNavigation.classList.add('hidden');
                
                // Update content header
                contentTitle.textContent = 'Pengguna Dalam Talian';
                contentSubtitle.textContent = '3 pengguna dalam talian menunggu sokongan';
            });

            // Ticket Mode Button Click
                            ticketModeBtn.addEventListener('click', function() {
                // Update button states
                ticketModeBtn.classList.remove('inactive');
                ticketModeBtn.classList.add('active');
                
                chatModeBtn.classList.remove('active');
                chatModeBtn.classList.add('inactive');

                // Show/hide content interfaces
                                ticketInterface.classList.remove('hidden');
                                chatInterface.classList.add('hidden');
                
                // Show/hide navigation sections
                ticketNavigation.classList.remove('hidden');
                chatNavigation.classList.add('hidden');
                
                // Update content header
                contentTitle.textContent = 'Semua Tiket';
                contentSubtitle.textContent = '30 tiket aktif menunggu tindakan';
            });

            // Chat functionality
            const chatInput = document.getElementById('chatInput');
            const chatSendBtn = document.getElementById('chatSendBtn');
            const chatMessages = document.getElementById('chatMessages');
            const typingIndicator = document.getElementById('typingIndicator');

            // Auto-resize textarea
            chatInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                
                // Enable/disable send button
                chatSendBtn.disabled = this.value.trim() === '';
            });

            // Send message on Enter (but allow Shift+Enter for new line)
            chatInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!chatSendBtn.disabled) {
                        sendMessage();
                    }
                }
            });
        });

        // Chat Menu Functions
        function toggleChatMenu() {
            const menu = document.getElementById('chatDropdownMenu');
            menu.classList.toggle('hidden');
        }

        function toggleVipStatus() {
            const statusText = document.querySelector('.chat-status-text');
            const currentStatus = statusText.textContent;
            
            if (currentStatus.includes('VIP User')) {
                statusText.textContent = currentStatus.replace('VIP User', 'Normal User');
                alert('Status ditukar kepada Normal User');
            } else {
                statusText.textContent = currentStatus.replace('Normal User', 'VIP User');
                alert('Status ditukar kepada VIP User');
            }
            toggleChatMenu();
        }

        function setPriority() {
            const badge = document.getElementById('chatPriorityBadge');
            badge.textContent = 'Tinggi';
            badge.className = 'text-xs px-2 py-1 rounded-full font-medium bg-orange-100 text-orange-800';
            alert('Prioriti ditetapkan sebagai Tinggi');
            toggleChatMenu();
        }

        function transferChat() {
            alert('Transfer ke Agent Lain - Feature akan dilaksanakan');
            toggleChatMenu();
        }

        function escalateChat() {
            alert('Escalate ke Supervisor - Feature akan dilaksanakan');
            toggleChatMenu();
        }

        function viewUserProfile() {
            alert('Lihat Profil Pengguna - Feature akan dilaksanakan');
            toggleChatMenu();
        }

        function viewChatHistory() {
            alert('Sejarah Chat - Feature akan dilaksanakan');
            toggleChatMenu();
        }

        function exportChatLog() {
            alert('Export Chat Log - Feature akan dilaksanakan');
            toggleChatMenu();
        }

        function endChat() {
            if (confirm('Adakah anda pasti mahu menamatkan sembang ini?')) {
                alert('Sembang ditamatkan');
                closeChat();
            }
            toggleChatMenu();
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('chatDropdownMenu');
            const button = document.querySelector('.chat-menu-btn');
            
            if (menu && !menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Chat functions
        function openChat(userName, priority, description, masjidId = 'U9M9HU', ipAddress = '192.168.1.100', lokasi = 'Kuala Lumpur', status = 'Normal User') {
            const chatInterface = document.getElementById('chatInterface');
            const ticketInterface = document.getElementById('ticketInterface');
            const inlineChatInterface = document.getElementById('inlineChatInterface');
            const mainContentArea = document.getElementById('mainContentArea');
            const chatUserName = document.getElementById('chatUserName');
            const chatPriorityBadge = document.getElementById('chatPriorityBadge');
            const contentTitle = document.getElementById('contentTitle');
            const contentSubtitle = document.getElementById('contentSubtitle');
            
            // Hide user list and ticket interface
            chatInterface.classList.add('hidden');
            ticketInterface.classList.add('hidden');
            
            // Show inline chat interface
            inlineChatInterface.classList.remove('hidden');
            
            // Add chat-active class to hide content header
            mainContentArea.classList.add('chat-active');
            
            // Also add to parent container for easier CSS targeting
            const rightContentArea = mainContentArea.closest('.flex-1');
            if (rightContentArea) {
                rightContentArea.classList.add('chat-mode');
            }
            
            console.log('Chat opened - added chat-active class');
            
            // Update header to show chat mode (will be hidden)
            contentTitle.textContent = 'Chat Langsung';
            contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>Sedang berchat dengan ' + userName;
            
            // Update chat user info
            chatUserName.textContent = userName;
            chatPriorityBadge.textContent = priority;
            
            // Update status text with Masjid ID, IP Address, Location, and User Status
            const chatStatusText = document.querySelector('.chat-status-text');
            if (chatStatusText) {
                chatStatusText.textContent = `Dalam Talian • Masjid Id : ${masjidId} • IP Address : ${ipAddress} • Lokasi : ${lokasi} • Status : ${status}`;
            }
            
            // Set priority badge color
            chatPriorityBadge.className = 'text-xs px-2 py-1 rounded-full font-medium';
            if (priority === 'Kecemasan') {
                chatPriorityBadge.classList.add('bg-red-100', 'text-red-800');
            } else if (priority === 'Tinggi') {
                chatPriorityBadge.classList.add('bg-orange-100', 'text-orange-800');
            } else {
                chatPriorityBadge.classList.add('bg-blue-100', 'text-blue-800');
            }
            
            // Auto-scroll to bottom
            setTimeout(() => {
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
        }

        function closeChat() {
            const chatInterface = document.getElementById('chatInterface');
            const ticketInterface = document.getElementById('ticketInterface');
            const inlineChatInterface = document.getElementById('inlineChatInterface');
            const mainContentArea = document.getElementById('mainContentArea');
            const contentTitle = document.getElementById('contentTitle');
            const contentSubtitle = document.getElementById('contentSubtitle');
            const chatModeBtn = document.getElementById('chatModeBtn');
            const ticketModeBtn = document.getElementById('ticketModeBtn');
            
            // Hide inline chat interface
            inlineChatInterface.classList.add('hidden');
            
            // Remove chat-active class to show content header again
            mainContentArea.classList.remove('chat-active');
            
            // Also remove from parent container
            const rightContentArea = mainContentArea.closest('.flex-1');
            if (rightContentArea) {
                rightContentArea.classList.remove('chat-mode');
            }
            
            // Show appropriate interface based on active mode
            if (chatModeBtn.classList.contains('inactive')) {
                // Ticket mode is active
                ticketInterface.classList.remove('hidden');
                contentTitle.textContent = 'Sistem Tiket';
                contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>Menguruskan tiket sokongan';
            } else {
                // Chat mode is active
                chatInterface.classList.remove('hidden');
                contentTitle.textContent = 'Pengguna Dalam Talian';
                contentSubtitle.innerHTML = '<span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>3 pengguna dalam talian menunggu sokongan';
            }
        }

        function sendMessage() {
            const chatInput = document.getElementById('chatInput');
            const chatMessages = document.getElementById('chatMessages');
            const chatSendBtn = document.getElementById('chatSendBtn');
            const typingIndicator = document.getElementById('typingIndicator');
            
            const messageText = chatInput.value.trim();
            if (messageText === '') return;

            // Ensure we have the chat messages container
            if (!chatMessages) {
                console.error('Chat messages container not found!');
                return;
            }

            // Create message element with proper structure
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message-item chat-message-sent';
            
            const now = new Date();
            const timeString = now.toLocaleTimeString('ms-MY', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });

            messageDiv.innerHTML = `
                <div class="chat-bubble chat-bubble-sent">
                    <p class="chat-message-text">${messageText}</p>
                    <p class="chat-message-time chat-message-time-sent">${timeString}</p>
                </div>
            `;

            // Remove typing indicator if visible
            if (typingIndicator) {
                typingIndicator.style.display = 'none';
            }

            // Add message to chat - insert before typing indicator if it exists
            if (chatMessages) {
                if (typingIndicator && typingIndicator.parentNode === chatMessages) {
                    chatMessages.insertBefore(messageDiv, typingIndicator);
                } else {
                    chatMessages.appendChild(messageDiv);
                }
                
                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Clear input and reset
            chatInput.value = '';
            chatInput.style.height = 'auto';
            chatSendBtn.disabled = true;

            // Show realistic live typing indicator
            setTimeout(() => {
                showTypingIndicator();
                // Random typing duration between 3-7 seconds for realism
                const typingDuration = 3000 + Math.random() * 4000;
                setTimeout(() => {
                    hideTypingIndicator();
                    addBotResponse();
                }, typingDuration);
            }, 800); // Small delay before showing typing
        }

        function showTypingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            const chatMessages = document.getElementById('chatMessages');
            
            // Show typing indicator with smooth animation
            typingIndicator.classList.remove('hidden');
            typingIndicator.style.display = 'flex';
            
            // Auto-scroll to bottom to show typing indicator
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 50);
        }

        function hideTypingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            typingIndicator.style.display = 'none';
            typingIndicator.classList.add('hidden');
        }

        function addBotResponse() {
            const chatMessages = document.getElementById('chatMessages');
            
            // Debug: Check if we have the right element
            console.log('Bot response - chatMessages element:', chatMessages);
            console.log('Bot response - chatMessages className:', chatMessages ? chatMessages.className : 'not found');
            
            const responses = [
                "Terima kasih atas maklumat tambahan. Saya sedang menyemak sistem untuk mengenal pasti punca masalah ini.",
                "Saya faham masalah anda. Boleh anda cuba clear cache browser dan cuba lagi?",
                "Masalah ini mungkin berkaitan dengan konfigurasi server. Saya akan escalate kepada team teknikal.",
                "Sila cuba upload fail yang sama menggunakan browser lain seperti Firefox atau Edge untuk test.",
                "Saya telah log masalah ini. Anda akan menerima update dalam masa 30 minit."
            ];
            
            const randomResponse = responses[Math.floor(Math.random() * responses.length)];
            
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message-item chat-message-received';
            
            const now = new Date();
            const timeString = now.toLocaleTimeString('ms-MY', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });

            messageDiv.innerHTML = `
                <div class="chat-bubble chat-bubble-received">
                    <p class="chat-message-text">${randomResponse}</p>
                    <p class="chat-message-time chat-message-time-received">${timeString}</p>
            </div>
            `;

            // Debug: Check before adding
            console.log('Bot response - About to add messageDiv:', messageDiv);
            console.log('Bot response - chatMessages children before:', chatMessages.children.length);
            
            if (chatMessages) {
                const typingIndicator = document.getElementById('typingIndicator');
                if (typingIndicator && typingIndicator.parentNode === chatMessages) {
                    chatMessages.insertBefore(messageDiv, typingIndicator);
                } else {
                    chatMessages.appendChild(messageDiv);
                }
                
                console.log('Bot response - chatMessages children after:', chatMessages.children.length);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        // ===== EMOJI MART INTEGRATION =====
        let emojiPickerInstance = null;
        
        // Initialize Emoji Mart Picker
        function initializeEmojiPicker() {
            if (!emojiPickerInstance && window.EmojiMart) {
                const pickerContainer = document.getElementById('emojiPicker');
                
                // Create emoji-mart picker
                emojiPickerInstance = new window.EmojiMart.Picker({
                    onEmojiSelect: (emoji) => {
                        insertEmoji(emoji.native);
                        // Hide picker after selection
                        pickerContainer.classList.remove('show');
                    },
                    theme: 'light',
                    locale: 'en',
                    previewPosition: 'none',
                    skinTonePosition: 'search',
                    searchPosition: 'sticky',
                    navPosition: 'top',
                    perLine: 8,
                    maxFrequentRows: 2,
                    set: 'native',
                    autoFocus: false,
                    categoryIcons: {
                        activity: '⚽',
                        custom: '⭐',
                        flags: '🏁',
                        foods: '🍎',
                        frequent: '🕐',
                        nature: '🌿',
                        objects: '💡',
                        people: '😀',
                        places: '🏠',
                        symbols: '❤️'
                    }
                });
                
                // Append to container
                pickerContainer.appendChild(emojiPickerInstance);
            }
        }
        
        // Initialize picker when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for emoji-mart to load
            if (window.EmojiMart) {
                initializeEmojiPicker();
            } else {
                // Retry after a short delay if EmojiMart isn't loaded yet
                setTimeout(() => {
                    if (window.EmojiMart) {
                        initializeEmojiPicker();
                    }
                }, 500);
            }
        });

        function toggleEmojiPicker() {
            const picker = document.getElementById('emojiPicker');
            
            // Initialize picker if not already done
            if (!emojiPickerInstance) {
                initializeEmojiPicker();
            }
            
            if (picker.classList.contains('show')) {
                picker.classList.remove('show');
            } else {
                picker.classList.add('show');
            }
        }

        function insertEmoji(emoji) {
            const chatInput = document.getElementById('chatInput');
            const currentValue = chatInput.value;
            const cursorPos = chatInput.selectionStart;
            
            // Insert emoji at cursor position
            const newValue = currentValue.slice(0, cursorPos) + emoji + currentValue.slice(cursorPos);
            chatInput.value = newValue;
            
            // Set cursor position after emoji
            chatInput.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
            chatInput.focus();
            
            // Enable send button if there's content
            const chatSendBtn = document.getElementById('chatSendBtn');
            chatSendBtn.disabled = chatInput.value.trim() === '';
            
            // Hide emoji picker
            document.getElementById('emojiPicker').classList.remove('show');
        }

        // Close emoji picker when clicking outside
        document.addEventListener('click', function(event) {
            const picker = document.getElementById('emojiPicker');
            const emojiBtn = document.querySelector('.chat-emoji-btn');
            
            if (picker && emojiBtn && !picker.contains(event.target) && !emojiBtn.contains(event.target)) {
                picker.classList.remove('show');
            }
        });

                    </script>

    <!-- Emoji Mart JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>

    <x-footer />
</body>
</html>