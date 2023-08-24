<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FormControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testLoginFailed(): void
    {
        $response = $this->post('/form/login', [
            'username' => '',
            'password' => '',
        ]);
        $response->assertStatus(400);
    }
    public function testLoginSuccess(): void
    {
        $response = $this->post('/form/login', [
            'username' => 'daud',
            'password' => 'daud28@hidayat',
        ]);
        $response->assertStatus(200);
    }
    public function testFormFailed(): void
    {
        $response = $this->post('/form', [
            'username' => '',
            'password' => '',
        ]);
        $response->assertStatus(302);
        Log::info($response->status());
    }
    public function testFormSuccess(): void
    {
        $response = $this->post('/form', [
            'username' => 'daud',
            'password' => 'daud28@hidayat',
        ]);
        $response->assertStatus(200);
    }
}
