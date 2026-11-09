<?php

namespace Tests;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_base_endpoint_returns_a_successful_response()
    {
        $this->get('/');

        $this->assertSame(200, $this->response->getStatusCode());
        $this->assertSame(
            ['hi' => 'funcionou'],
            json_decode($this->response->getContent(), true)
        );
    }
}
