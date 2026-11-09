<?php

namespace Tests\Unit;

use App\Http\Controllers\UsuarioController;
use App\Http\Requests\UsuarioRequest;
use App\Services\UsuarioService;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class UsuarioControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_update_ficha_returns_success_when_service_updates_the_sheet(): void
    {
        $service = Mockery::mock(UsuarioService::class);
        $service->shouldReceive('updateFicha')
            ->once()
            ->with(7, ['nome' => 'Ficha'])
            ->andReturn(true);
        $controller = new UsuarioController(new UsuarioRequest, $service);

        $response = $controller->updateFicha(Request::create('/api/update-ficha', 'PUT', [
            'user_id' => 7,
            'ficha' => ['nome' => 'Ficha'],
        ]));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            ['message' => 'Ficha atualizada com sucesso!'],
            json_decode($response->getContent(), true)
        );
    }

    public function test_update_ficha_returns_not_found_when_service_cannot_find_the_user(): void
    {
        $service = Mockery::mock(UsuarioService::class);
        $service->shouldReceive('updateFicha')->once()->andReturn(false);
        $controller = new UsuarioController(new UsuarioRequest, $service);

        $response = $controller->updateFicha(Request::create('/api/update-ficha', 'PUT', [
            'user_id' => 7,
            'ficha' => ['nome' => 'Ficha'],
        ]));

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame(
            ['message' => 'Usuário não encontrado'],
            json_decode($response->getContent(), true)
        );
    }
}
