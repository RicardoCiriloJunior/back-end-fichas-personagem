<?php

namespace Tests\Unit;

use App\Repositories\UsuarioRepository;
use App\Services\UsuarioService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class UsuarioServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_login_returns_null_when_email_does_not_exist(): void
    {
        $repository = Mockery::mock(UsuarioRepository::class);
        $repository->shouldReceive('findByEmail')
            ->once()
            ->with('nao-existe@example.com')
            ->andReturn(null);

        $service = new UsuarioService($repository);

        $this->assertNull($service->validateLogin('nao-existe@example.com', 'senha'));
    }

    public function test_registration_returns_null_when_email_is_already_registered(): void
    {
        $repository = Mockery::mock(UsuarioRepository::class);
        $repository->shouldReceive('userExists')
            ->once()
            ->with('existente@example.com')
            ->andReturn(true);
        $repository->shouldNotReceive('createUsuario');

        $service = new UsuarioService($repository);

        $this->assertNull($service->createRegister([
            'name' => 'Pessoa',
            'email' => 'existente@example.com',
            'password' => 'senha',
        ]));
    }

    public function test_update_ficha_returns_false_when_user_does_not_exist(): void
    {
        $repository = Mockery::mock(UsuarioRepository::class);
        $repository->shouldReceive('findById')->once()->with(42)->andReturn(null);
        $repository->shouldNotReceive('save');

        $service = new UsuarioService($repository);

        $this->assertFalse($service->updateFicha(42, ['nome' => 'Ficha']));
    }

    public function test_get_usuario_data_wraps_a_missing_user_in_the_usuario_key(): void
    {
        $repository = Mockery::mock(UsuarioRepository::class);
        $repository->shouldReceive('findById')->once()->with(42)->andReturn(null);

        $service = new UsuarioService($repository);

        $this->assertSame(['usuario' => null], $service->getUsuarioData(42));
    }
}
