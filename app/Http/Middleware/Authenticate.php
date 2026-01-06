<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');
        if (!$authorization) {
            return response()->json(['message' => 'Token não fornecido!'], 401);
        }
        $parts = explode(' ', $authorization);
        if (count($parts) !== 2 || $parts[0] !== 'Bearer') {
            return response()->json(['message' => 'Formato do token inválido!'], 401);
        }

        $token = $parts[1];
        try {
            $payload = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $userId = $payload->sub;

            if (!Usuario::where('id', $userId)->exists()) {
                return response()->json(['message' => 'Esse usuário não existe!']);
            }

            $request->merge(['user_id' => $userId]);
        } catch (\Exception $e) {
            return  response()->json(['message' => 'Token inválido!'], 401);
        }

        return $next($request);
    }

}
