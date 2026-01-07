<?php
namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;

class UsuarioController extends BaseController
{
    public function __construct(private readonly UsuarioRequest $validator)
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

        return response()->json([
            'usuario' => $usuario,
            'token' => $this->generateToken($usuario)
        ]);
    }
    public function createRegister(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateRegister($request);

        if (Usuario::where('email', $validatedData['email'])->exists()){
            return response()->json(['message' => 'Esse usuário já existe!'], 409);
        }

        $usuario = Usuario::create([
            'nome' => $validatedData['name'],
            'email' => $validatedData['email'],
            'senha' => Hash::make($validatedData['password']),
            'ficha' => []
        ]);

        return response()->json([
            'message' => 'Usuario criado com sucesso',
            'usuario' => $usuario
        ], 201);
    }
    public function generateToken(Usuario $usuario): string
    {
        $payload = [
            'sub' => $usuario->id,
            'iat' => time(),
            'exp' => time() + (10 * 3600)
        ];

        return JWT::encode(
            $payload,
            env('JWT_SECRET'),
            'HS256'
        );
    }

    public function updateFicha(Request $request)
    {
        $validatedData = $this->validator->validateUpdateFicha($request);

        $usuario = Usuario::where('id', $validatedData['user_id'])->first();
        $usuario->ficha = $validatedData['ficha'];
        $usuario->save();

        return response()->json(['message' => 'Ficha atualizada com sucesso!']);
    }
}

