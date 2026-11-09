<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

class UsuarioController extends BaseController
{
    public function __construct(private readonly UsuarioRequest $validator, private readonly UsuarioService $service)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Autenticação"},
     *     summary="Autentica um usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Autenticação realizada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciais incorretas", @OA\JsonContent(ref="#/components/schemas/Message")),
     *     @OA\Response(response=422, description="Campos inválidos", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function validateLogin(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateLogin($request);

        $result = $this->service->validateLogin(
            $validatedData['email'],
            $validatedData['password']
        );

        if (! $result) {
            return response()->json([
                'message' => 'Informações incorretas! Tente novamente!',
            ], 401);
        }

        return response()->json($result);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Autenticação"},
     *     summary="Cadastra um usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", maxLength=60),
     *             @OA\Property(property="email", type="string", format="email", maxLength=254),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuário criado", @OA\JsonContent(ref="#/components/schemas/Message")),
     *     @OA\Response(response=409, description="E-mail já cadastrado", @OA\JsonContent(ref="#/components/schemas/Message")),
     *     @OA\Response(response=422, description="Campos inválidos", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function createRegister(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateRegister($request);

        $usuario = $this->service->createRegister($validatedData);

        if (! $usuario) {
            return response()->json(['message' => 'Esse usuário já existe!'], 409);
        }

        return response()->json([
            'message' => 'Usuário criado com sucesso',
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/update-ficha",
     *     tags={"Ficha"},
     *     summary="Atualiza a ficha do usuário autenticado",
     *     description="O identificador do usuário vem do JWT validado pelo middleware.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ficha"},
     *             @OA\Property(property="ficha", type="object", additionalProperties=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Ficha atualizada", @OA\JsonContent(ref="#/components/schemas/Message")),
     *     @OA\Response(response=401, description="Token ausente ou inválido", @OA\JsonContent(ref="#/components/schemas/Message")),
     *     @OA\Response(response=404, description="Usuário não encontrado", @OA\JsonContent(ref="#/components/schemas/Message")),
     *     @OA\Response(response=422, description="Campos inválidos", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function updateFicha(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateUpdateFicha($request);
        $updated = $this->service->updateFicha(
            $validatedData['user_id'],
            $validatedData['ficha']
        );

        if (! $updated) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        return response()->json(['message' => 'Ficha atualizada com sucesso!']);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     tags={"Usuário"},
     *     summary="Consulta os dados do usuário autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário; quando não há registro, usuario é null. O middleware também responde 200 com uma mensagem quando o usuário do token não existe.",
     *         @OA\JsonContent(ref="#/components/schemas/UsuarioEnvelope")
     *     ),
     *     @OA\Response(response=401, description="Token ausente ou inválido", @OA\JsonContent(ref="#/components/schemas/Message")),
     *     @OA\Response(response=422, description="Campos inválidos", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function getUsuarioData(Request $request): JsonResponse
    {
        $validatedData = $this->validator->validateGetUsuarioData($request);
        $usuario = $this->service->getUsuarioData($validatedData['user_id']);

        if (! $usuario) {
            return response()->json(['message' => 'Usuário não encontrado']);
        }

        return response()->json($usuario);
    }

    public function sendPasswordResetCode(Request $request): JsonResponse
    {
        return response()->json();
    }
}

