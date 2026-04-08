<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Если пользователь авторизован И его роль 'admin' — пропускаем дальше
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // Иначе — «от ворот поворот» (Ошибка 403 Forbidden)
        return response()->json(['message' => 'У вас нет прав администратора'], 403);
    }
}
