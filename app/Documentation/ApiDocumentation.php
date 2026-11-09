<?php

namespace App\Documentation;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de Fichas de Personagem",
 *     version="1.0.0",
 *     description="API para cadastro, autenticação e gerenciamento de fichas."
 * )
 * @OA\Server(url="/")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Schema(
 *     schema="Message",
 *     type="object",
 *     @OA\Property(property="message", type="string")
 * )
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="nome", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="ficha", type="object", additionalProperties=true)
 * )
 * @OA\Schema(
 *     schema="UsuarioEnvelope",
 *     type="object",
 *     @OA\Property(
 *         property="usuario",
 *         ref="#/components/schemas/Usuario",
 *         nullable=true
 *     )
 * )
 * @OA\Schema(
 *     schema="ValidationError",
 *     type="object",
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 */
class ApiDocumentation
{
}
