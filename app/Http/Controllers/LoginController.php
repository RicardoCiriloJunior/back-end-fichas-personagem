<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Requests\LoginRequest;
use App\Models\Usuario;

class LoginController extends BaseController
{
    public function __construct(private readonly LoginRequest $validator)
    {
    }

    public function validateLogin(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateLogin($request);

        $usuario = Usuario::where('email', $validatedData['email'])->first();

        if (!$usuario || !Hash::check($validatedData['password'], $usuario->senha)){
            return response()->json([
                'message' => 'Informações incorretas! Tente novamente!'
            ], 401);
        }

        return response()->json($usuario);
    }
}

