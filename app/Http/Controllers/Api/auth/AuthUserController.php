<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthUserController extends Controller
{

    /**
     * Get Auth user information.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user =  $request->user();

        if ($user) {
            return response()->json($user, Response::HTTP_OK);
        }

        return response()->json(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Refresh a token.
     */
    public function refresh(Request $request): JsonResponse
    {
        $user =  $request->user();
        if ($user) {
            // Revoke all tokens...
            $user->tokens()->delete();

            // Create a new token
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
            ], Response::HTTP_OK);
        }

        return response()->json(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(Request $request): JsonResponse
    {
        $user =  $request->user();
        if ($user) {
            // Revoke all tokens...
            $user->tokens()->delete();

            return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
        }

        return response()->json(['error' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
    }
}
