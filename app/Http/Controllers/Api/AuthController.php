<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());   // password is hashed by the model cast

        return $this->tokenResponse($user, false, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        return $this->tokenResponse($user, $request->boolean('read_only'), 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    private function tokenResponse(User $user, bool $readOnly, int $status): JsonResponse
    {
        $token = $user->createToken(
            $readOnly ? 'read-only' : 'full-access',
            $readOnly ? ['books:read'] : ['books:read', 'books:write']
        );

        return response()->json([
            'data' => [
                'token'      => $token->plainTextToken,
                'token_type' => 'Bearer',
                'abilities'  => $token->accessToken->abilities,
            ],
        ], $status);
    }
}
}
