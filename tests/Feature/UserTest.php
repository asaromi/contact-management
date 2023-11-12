<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'anjay name',
            'password' => 'rahasia',
            'name' => 'Asa Romi'
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'username' => 'anjay name',
                    'name' => 'Asa Romi'
                ],
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => 'anjay',
            'name' => 'Asa Romi'
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'anjay name',
            'password' => 'yang laen',
            'name' => 'Agak Laen'
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'username' => ['The username has already been taken.']
                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/users/login', [
            'username' => 'test-username',
            'password' => 'testPassword',
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test-username',
                    'name' => 'Testing User'
                ]
            ]);

        $user_token = User::where('username', 'test-username')->value('token');
        self::assertNotNull($user_token);
    }

    public function testLoginFailed()
    {

    }
}
