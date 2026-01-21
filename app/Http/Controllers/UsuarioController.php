<?php
namespace App\Http\Controllers;

use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Requests\UsuarioRequest;

class UsuarioController extends BaseController
{
    public function __construct(private readonly UsuarioRequest $validator, private readonly UsuarioService $service)
    {
    }

    public function validateLogin(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateLogin($request);

        $result = $this->service->validateLogin(
            $validatedData['email'],
            $validatedData['password']
        );

        if (!$result) {
            return response()->json([
                'message' => 'Informações incorretas! Tente novamente!'
            ], 401);
        }

        return response()->json($result);
    }
    public function createRegister(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateRegister($request);

        $usuario = $this->service->createRegister($validatedData);

        if (!$usuario) {
            return response()->json(['message' => 'Esse usuário já existe!'], 409);
        }

        return response()->json([
            'message' => 'Usuário criado com sucesso'
        ], 201);
    }

    public function updateFicha(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateUpdateFicha($request);
        $updated = $this->service->updateFicha(
            $validatedData['user_id'],
            $validatedData['ficha']
        );

        if (!$updated) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['message' => 'Ficha atualizada com sucesso!']);
    }
    public function getUsuarioData(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateGetUsuarioData($request);
        $usuario = $this->service->getUsuarioData($validatedData['user_id']);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado']);
        }

        return response()->json($usuario);
    }
    public function sendPasswordResetCode(Request $request): JsonResponse
    {
        return response()->json();
    }
}

