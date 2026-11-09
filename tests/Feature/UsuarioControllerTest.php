<?php

namespace Tests\Feature;

use App\Services\UsuarioService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class UsuarioControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_login_returns_a_token_when_credentials_are_valid(): void
    {
        $service = Mockery::mock(UsuarioService::class);
        $service->shouldReceive('validateLogin')
            ->once()
            ->with('pessoa@example.com', 'senha-segura')
            ->andReturn(['token' => 'jwt-exemplo']);
        $this->app->instance(UsuarioService::class, $service);

        $this->post('/api/login', [
            'email' => 'pessoa@example.com',
            'password' => 'senha-segura',
        ]);

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertSame(['token' => 'jwt-exemplo'], $this->responseJson());
    }

    public function test_login_returns_unauthorized_when_credentials_are_invalid(): void
    {
        $service = Mockery::mock(UsuarioService::class);
        $service->shouldReceive('validateLogin')->once()->andReturn(null);
        $this->app->instance(UsuarioService::class, $service);

        $this->post('/api/login', [
            'email' => 'pessoa@example.com',
            'password' => 'incorreta',
        ]);

        $this->assertSame(401, $this->response->getStatusCode());
        $this->assertSame(
            ['message' => 'Informações incorretas! Tente novamente!'],
            $this->responseJson()
        );
    }

    public function test_register_returns_created_when_service_creates_the_user(): void
    {
        $service = Mockery::mock(UsuarioService::class);
        $service->shouldReceive('createRegister')->once()->andReturn((object) ['id' => 1]);
        $this->app->instance(UsuarioService::class, $service);

        $this->post('/api/register', [
            'name' => 'Pessoa',
            'email' => 'pessoa@example.com',
            'password' => 'senha-segura',
            'password_confirmation' => 'senha-segura',
        ]);

        $this->assertSame(201, $this->response->getStatusCode());
        $this->assertSame(['message' => 'Usuário criado com sucesso'], $this->responseJson());
    }

    public function test_register_returns_conflict_when_email_already_exists(): void
    {
        $service = Mockery::mock(UsuarioService::class);
        $service->shouldReceive('createRegister')->once()->andReturn(null);
        $this->app->instance(UsuarioService::class, $service);

        $this->post('/api/register', [
            'name' => 'Pessoa',
            'email' => 'pessoa@example.com',
            'password' => 'senha-segura',
            'password_confirmation' => 'senha-segura',
        ]);

        $this->assertSame(409, $this->response->getStatusCode());
        $this->assertSame(['message' => 'Esse usuário já existe!'], $this->responseJson());
    }

    public function test_login_returns_validation_error_when_email_is_missing(): void
    {
        $this->post('/api/login', ['password' => 'senha-segura']);

        $this->assertSame(422, $this->response->getStatusCode());
        $this->assertSame('Preencha os campos corretamente!', $this->responseJson()['message']);
        $this->assertArrayHasKey('email', $this->responseJson()['errors']);
    }

    public function test_protected_routes_reject_requests_without_a_token(): void
    {
        $this->get('/api/me');

        $this->assertSame(401, $this->response->getStatusCode());
        $this->assertSame(['message' => 'Token não fornecido!'], $this->responseJson());
    }

    private function responseJson(): array
    {
        return json_decode($this->response->getContent(), true);
    }
}
