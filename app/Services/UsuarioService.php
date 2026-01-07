<?php

namespace App\Services;

use App\Models\Usuario;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UsuarioRepository;
class UsuarioService
{
    public function __construct(private readonly UsuarioRepository $repository)
    {
    }

    public function validateLogin(string $email, string $password): ?array
    {
        $usuario = $this->repository->findByEmail($email);

        if (!$usuario || !Hash::check($password, $usuario->senha)){
            return null;
        }

        $token = $this->generateToken($usuario);

        return [
            'usuario' => $usuario,
            'token' => $token
        ];
    }

    public function createRegister(array $data)
    {
        if ($this->repository->userExists($data['email'])) {
            return null;
        }
        $data['password'] = Hash::make($data['password']);

        return $this->repository->createUsuario($data);

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

    public function updateFicha(int $userId, array $ficha): bool
    {
        $usuario = $this->repository->findById($userId);

        if (!$usuario) return false;

        $usuario->ficha = $ficha;
        return $this->repository->save($usuario);
    }
}
