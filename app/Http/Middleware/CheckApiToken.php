<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenFromHeader = $request->header('X-CSRF-TOKEN'); // Получаем токен из заголовка
        //$tokenFromSession = Session::get('csrfToken'); // Получаем токен из сессии

        // Если токен отсутствует в заголовке или в сессии, пропускаем проверку
        /*if (is_null($tokenFromHeader) || is_null($tokenFromSession)) {
            return $next($request);
        }*/

        // Если токены не совпадают, возвращаем ошибку
       /* if ($tokenFromHeader !== $tokenFromSession) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }*/

        return $next($request);
    }
}
