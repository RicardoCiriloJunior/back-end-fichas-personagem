<?php

namespace Tests\Feature;

use Tests\TestCase;

class SwaggerDocumentationTest extends TestCase
{
    public function test_swagger_ui_route_is_available(): void
    {
        $this->get('/api/documentation');

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertStringContainsString('swagger-ui', $this->response->getContent());
    }

    public function test_generated_openapi_document_contains_the_api_endpoints(): void
    {
        $this->get('/docs');

        $this->assertSame(200, $this->response->getStatusCode());
        $document = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('/api/login', $document['paths']);
        $this->assertArrayHasKey('/api/register', $document['paths']);
        $this->assertArrayHasKey('/api/me', $document['paths']);
        $this->assertArrayHasKey('/api/update-ficha', $document['paths']);
    }
}
