<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UsuarioRequest
{
    public function validateLogin(Request $request): array
    {
        return Validator::make($request->all(), [
            'email' => [
                'required',
                'email'
            ],
            'password' => [
                'required',
                'string'
            ]
        ])->validate();
    }
    public function validateRegister(Request $request): array
    {
        return Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:60'
            ],
            'email' => [
                'required',
                'email',
                'max:254'
            ],
            'password' => [
                'required',
                'string',
                'confirmed'
            ],

        ])->validate();
    }
    public function validateUpdateFicha(Request $request): array
    {
        return Validator::make($request->all(), [
            'ficha' => [
                'required',
                'array'
            ],
            'user_id' => [
                'required',
                'integer'
            ]
        ])->validate();
    }
    public function validateGetUsuarioData(Request $request): array
    {
        return Validator::make($request->all(), [
            'user_id' => [
                'required',
                'integer'
            ]
        ])->validate();
    }
    public function validateRequestPasswordReset(Request $request): array
    {
        return [];
    }
}
