<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginWithValidCredentials()
    {
        $response = $this->call('POST', '/api/login', [
            'username' => 'admin',
            'password' => 'admin@gmail.com'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "code",
            "message",
            "token",
            "token_type",
            "expires_in",
        ]);
    }

    public function testLoginWithInvalidCredentials()
    {
        $response = $this->call('POST', '/api/login', [
            'username' => 'admin111',
            'password' => 'admin@gmail.com'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            "code",
            "message",
        ]);
    }

    public function testSuccessLogout() {
        $login = $this->call('POST', '/api/login', [
            'username' => 'admin',
            'password' => 'admin@gmail.com'
        ]);
        $token = $login->json('token');
        $response = $this->call('POST', '/api/logout', [], [], [], ['HTTP_Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "code",
            "message",
        ]);
    }
    public function testFailLogout() {
        $response = $this->call('POST', '/api/logout');
        $response->assertStatus(401);
    }
}
