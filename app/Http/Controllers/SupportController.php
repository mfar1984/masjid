<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display the support contact page
     */
    public function hubungiSokongan()
    {
        $user = Auth::user();
        $pageTitle = 'Hubungi Sokongan - E-Masjid';
        
        // This interface is for Masjid users only
        // Super Admin uses /support/dashboard instead
        $userMasjidId = $user->masjid_id;
        
        // For masjid users, only show basic chat interface
        return view('bantuan.hubungi-sokongan', compact(
            'user',
            'pageTitle',
            'userMasjidId'
        ));
    }
    
    /**
     * Display the support dashboard (Super Admin only)
     */
    public function dashboard()
    {
        $user = Auth::user();
        $pageTitle = 'Dashboard Sokongan - E-Masjid';

        // Check if user is Super Admin
        if (!$user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized access to support dashboard');
        }

        // DEBUG: Add debug info for Super Admin
        $isSuperAdmin = $user->hasRole('Super Admin');

        // Since SupportRequest model doesn't exist yet, use realistic dummy data
        // This includes the Ahmad Razak request that was submitted
        $dummySupportRequests = collect([
            [
                'id' => 1,
                'user_name' => 'Ahmad Razak',
                'masjid_name' => 'Masjid An-Nur',
                'email' => 'ahmad.razak@masjidannur.com',
                'phone' => '013-456-7890',
                'subject' => 'Masalah Upload Dokumen',
                'message' => 'Salam, saya menghadapi masalah untuk upload dokumen kariah. Sistem menunjukkan error "File too large" walaupun file saya hanya 2MB. Boleh tolong check?',
                'priority' => 'urgent',
                'category' => 'technical',
                'status' => 'open',
                'created_at' => now()->subMinutes(15)
            ],
            [
                'id' => 2,
                'user_name' => 'Siti Aminah',
                'masjid_name' => 'Masjid Ar-Rahman',
                'email' => 'siti.aminah@masjid.com',
                'phone' => '012-345-6789',
                'subject' => 'Setup Email Notifications',
                'message' => 'Bagaimana nak setup email notifications untuk sistem kariah?',
                'priority' => 'medium',
                'category' => 'feature',
                'status' => 'open',
                'created_at' => now()->subHours(2)
            ],
            [
                'id' => 3,
                'user_name' => 'Muhammad Ali',
                'masjid_name' => 'Masjid Al-Ikhlas',
                'email' => 'ali@masjid.com',
                'phone' => '019-876-5432',
                'subject' => 'Sistem Login Error',
                'message' => 'Sistem tidak boleh login sejak semalam. Dapat error 500.',
                'priority' => 'urgent',
                'category' => 'technical',
                'status' => 'open',
                'created_at' => now()->subHours(1)
            ]
        ]);

        // Sample data for dashboard
        $stats = [
            'active_chats' => $dummySupportRequests->where('status', 'open')->count(),
            'open_tickets' => $dummySupportRequests->where('status', 'open')->count(),
            'urgent_tickets' => $dummySupportRequests->where('priority', 'urgent')->count(),
            'avg_response_time' => '2.5m'
        ];

        // Convert support requests to active chats format
        $activeChats = $dummySupportRequests->where('status', 'open')->take(10)->map(function($request) {
            return [
                'id' => $request['id'],
                'masjid_name' => $request['masjid_name'],
                'admin_name' => $request['user_name'],
                'last_message' => \Illuminate\Support\Str::limit($request['message'], 50),
                'priority' => $request['priority'],
                'category' => $request['category'],
                'status' => 'online',
                'time_ago' => $request['created_at']->diffForHumans(),
                'email' => $request['email'],
                'phone' => $request['phone']
            ];
        })->toArray();

        // Convert support requests to ticket queue format
        $ticketQueue = $dummySupportRequests->take(5)->map(function($request) {
            return [
                'id' => 'TKT-' . str_pad($request['id'], 3, '0', STR_PAD_LEFT),
                'title' => \Illuminate\Support\Str::limit($request['subject'], 30),
                'masjid_name' => $request['masjid_name'],
                'priority' => $request['priority'],
                'time_ago' => $request['created_at']->diffForHumans()
            ];
        })->toArray();

        return view('bantuan.support-dashboard', compact(
            'user',
            'pageTitle',
            'stats',
            'activeChats',
            'ticketQueue',
            'isSuperAdmin',
            'dummySupportRequests'
        ));
    }
    
    /**
     * Display ticket detail page
     */
    public function ticketDetail($ticketId)
    {
        $user = Auth::user();
        $pageTitle = "Tiket #{$ticketId} - E-Masjid";
        
        // Check if user is Super Admin
        if (!$user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized access to ticket details');
        }
        
        // Sample ticket data
        $ticket = [
            'id' => $ticketId,
            'title' => 'Sistem tidak boleh login',
            'description' => 'Assalamualaikum. Kami menghadapi masalah dengan sistem login...',
            'status' => 'open',
            'priority' => 'urgent',
            'category' => 'technical',
            'created_at' => '2 hours ago',
            'updated_at' => '30 min ago',
            'masjid' => [
                'name' => 'Masjid Al-Ikhlas',
                'location' => 'Kuala Lumpur',
                'admin_name' => 'Ahmad Rahman',
                'admin_email' => 'admin@al-ikhlas.my',
                'admin_phone' => '03-1234 5678',
                'previous_tickets' => 3
            ],
            'attachments' => [
                [
                    'name' => 'error-screenshot.pdf',
                    'size' => '245 KB',
                    'type' => 'pdf'
                ]
            ],
            'timeline' => [
                [
                    'type' => 'created',
                    'user' => 'Ahmad Rahman',
                    'action' => 'membuat tiket',
                    'description' => 'Tiket telah dibuat dengan keutamaan Urgent',
                    'time_ago' => '2 jam lalu'
                ],
                [
                    'type' => 'response',
                    'user' => 'Sarah (Support)',
                    'action' => 'membalas',
                    'message' => 'Terima kasih atas laporan ini. Saya akan semak sistem login untuk Masjid Al-Ikhlas...',
                    'time_ago' => '1 jam lalu'
                ],
                [
                    'type' => 'status_update',
                    'user' => 'Sarah (Support)',
                    'action' => 'mengubah status',
                    'description' => 'Status diubah dari Terbuka ke Dalam Proses',
                    'time_ago' => '30 min lalu'
                ]
            ]
        ];
        
        return view('bantuan.ticket-detail', compact('user', 'pageTitle', 'ticket'));
    }
    
    /**
     * API endpoint to get support notifications
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is Super Admin
        if (!$user->hasRole('Super Admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Sample notifications data
        $notifications = [
            [
                'id' => 1,
                'type' => 'support_chat',
                'masjid_name' => 'Masjid Al-Falah',
                'message' => 'Saya ada masalah dengan upload dokumen...',
                'priority' => 'urgent',
                'time_ago' => '2 min ago',
                'read' => false
            ],
            [
                'id' => 2,
                'type' => 'support_ticket',
                'ticket_id' => 'TKT-001',
                'subject' => 'Sistem tidak boleh login',
                'masjid_name' => 'Masjid Ar-Rahman',
                'priority' => 'high',
                'time_ago' => '30 min ago',
                'read' => false
            ],
            [
                'id' => 3,
                'type' => 'system_alert',
                'message' => 'Multiple failed login attempts detected',
                'time_ago' => '1 hour ago',
                'read' => true
            ]
        ];
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }
    
    /**
     * API endpoint to mark all notifications as read
     */
    public function markAllNotificationsRead(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is Super Admin
        if (!$user->hasRole('Super Admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // In real implementation, update database
        // SupportNotification::where('user_id', $user->id)->update(['read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
}
