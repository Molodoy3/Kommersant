<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $maxAttempts = 5;
        if (RateLimiter::tooManyAttempts($request->ip(), $maxAttempts))
            return response()->json(['error' => 'Превышен лимит попыток входа. Попробуйте позже.'], 429);

        if (isset($request->name) && isset($request->password)) {
            $user = User::query()->where('name', $request->name)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                RateLimiter::hit($request->ip());
                return response()->json(['error' => 'Данные введены неправильно'], 401);
            }
            return response()->json([
                'token' => $user->createToken('api_token')->plainTextToken
            ]);
        }
        return response()->json(['error' => 'bad request'], 400);
    }
    public function checkApiToken(): JsonResponse
    {
        return response()->json(['authenticated' => Auth::check()]);
    }
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Вы успешно вышли']);
    }
}
