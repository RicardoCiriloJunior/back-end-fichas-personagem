<?php

namespace App\Repositories;

use App\Models\Usuario;
use App\Util\TemplateFicha;
use Illuminate\Support\Facades\Hash;


class UsuarioRepository
{
    public function findByEmail(string $email)
    {
        return Usuario::where('email', $email)->first();

    }

    public function findById(int $id)
    {
        return Usuario::find($id);
    }

    public function save($usuario)
    {
        return $usuario->save();
    }
    public function createUsuario(array $data)
    {
        $usuario = Usuario::create([
            'nome' => $data['name'],
            'email' => $data['email'],
            'senha' => $data['password'],
            'ficha' => TemplateFicha::$ficha
        ]);

        return $usuario;
    }

    public function userExists(string $email): bool
    {
        return Usuario::where('email', $email)->exists();
    }
}
