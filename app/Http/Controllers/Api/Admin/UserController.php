<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $maxAttempts = 5; // Максимальное количество попыток
        if (RateLimiter::tooManyAttempts($request->ip(), $maxAttempts))
            return response()->json(['error' => 'Превышен лимит попыток входа. Попробуйте позже.'], 429);

        if (Auth::attempt($request->only('name', 'password'))) {
            RateLimiter::clear($request->ip());
            $request->session()->regenerate();
            return response()->json([
                    'token' => $request->user()->createToken('api_token')->plainTextToken
                ]);
        }
        else {
            RateLimiter::hit($request->ip());
            return response()->json(['error' => 'Данные введены неправильно'], 401);
        }
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
