<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelloWorld extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_helloworld()
    {
        $response = $this->get('api/greeting');

        $response->assertStatus(200);

        $responde->assertSeeText('Hello World!');
    }
}
