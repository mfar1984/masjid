<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SanctumTokenController extends Controller
{
    // Middleware is applied in routes/web.php for better control

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $tokens = $user->tokens()
            ->latest('created_at')
            ->get(['id', 'name', 'abilities', 'last_used_at', 'created_at']);

        return response()->json([
            'success' => true,
            'tokens' => $tokens,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // Debug for testing
        if (app()->environment('testing')) {
            \Log::info('SanctumTokenController::store called', [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);
        }

        $validated = $request->validate([
            'token_name' => ['required', 'string', 'max:100'],
            'abilities' => ['nullable', 'array'],
            'expires_in_minutes' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = Auth::user();
        $abilities = $validated['abilities'] ?? ['*'];

        $token = $user->createToken($validated['token_name'], $abilities);

        // Debug token creation
        if (app()->environment('testing')) {
            \Log::info('Token created', [
                'token_id' => $token->accessToken->id ?? 'unknown',
                'token_name' => $validated['token_name'],
                'user_id' => $user->id
            ]);
        }

        return response()->json([
            'success' => true,
            'token' => $token->plainTextToken,
        ]);
    }

    public function destroyAll(): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua token telah dibatalkan.',
        ]);
    }
}
