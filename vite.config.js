import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

// Custom plugin to suppress Vite client logs
const suppressViteClientLogs = () => {
    return {
        name: 'suppress-vite-client-logs',
        configureServer(server) {
            // Override the WebSocket send method to filter out log messages
            const originalSend = server.ws.send;
            server.ws.send = function(payload) {
                // Don't send HMR connection messages to client
                if (typeof payload === 'string') {
                    const data = JSON.parse(payload);
                    if (data.type === 'connected' || data.type === 'connecting') {
                        return; // Suppress these messages
                    }
                }
                return originalSend.call(this, payload);
            };
        },
        transformIndexHtml: {
            enforce: 'pre',
            transform(html) {
                // Inject script to suppress client-side Vite logs
                return html.replace(
                    '<head>',
                    `<head>
                    <script>
                        // Suppress Vite client logs
                        if (typeof console !== 'undefined') {
                            const originalLog = console.log;
                            const originalInfo = console.info;
                            console.log = function(...args) {
                                const message = args.join(' ');
                                if (message.includes('[vite]') || message.includes('[HMR]')) {
                                    return; // Suppress Vite/HMR messages
                                }
                                return originalLog.apply(console, args);
                            };
                            console.info = function(...args) {
                                const message = args.join(' ');
                                if (message.includes('[vite]') || message.includes('[HMR]')) {
                                    return; // Suppress Vite/HMR messages
                                }
                                return originalInfo.apply(console, args);
                            };
                        }
                    </script>`
                );
            }
        }
    };
};

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        suppressViteClientLogs(), // Add our custom plugin
    ],
    server: {
        hmr: {
            overlay: false, // Disable error overlay
            clientPort: false, // Disable client port logging
        },
        // Disable server logs
        middlewareMode: false,
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
        // Disable build info logs
        reportCompressedSize: false,
    },
    // Disable all Vite client logs
    define: {
        __VITE_IS_MODERN__: true,
        __VITE_HMR_PROTOCOL__: JSON.stringify('ws'),
        // Completely disable client-side Vite logging
        'import.meta.env.DEV': false,
        'process.env.NODE_ENV': JSON.stringify('production'),
    },
    logLevel: 'silent', // Completely silent - no logs at all
    clearScreen: false, // Don't clear terminal screen
    // Additional client configuration to suppress HMR messages
    experimental: {
        hmrPartialAccept: false,
    }
});
