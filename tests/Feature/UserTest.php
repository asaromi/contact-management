<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{
    private function getTestUser(string $username): User
    {
        $query = User::where('username', $username);
        if (!$query->exists()) {
            $this->seed([UserSeeder::class]);
        }

        return $query->first();
    }

    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'anuuuu',
            'password' => 'rahasia',
            'name' => 'Asa Romi'
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'username' => 'anuuuu',
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
//        $this->testRegisterSuccess();
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
        $user = $this->getTestUser('test-username');

        $this->post('/api/users/login', [
            'username' => 'test-username',
            'password' => 'testPassword',
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => $user->username,
                    'name' => $user->name
                ]
            ]);

        $user->refresh();
        self::assertNotNull($user->token);
    }

    public function testLoginFailedUsernameNotFound()
    {
        $user = $this->getTestUser('test-username');

        $this->post('/api/users/login', [
            'username' => 'User sok asik', // not found username
            'password' => 'testPassword123', // unmatched password
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['username or password wrong']
                ]
            ]);
    }

    public function testLoginFailedWrongPassword()
    {
        $user = $this->getTestUser('test-username');

        $this->post('/api/users/login', [
            'username' => 'test-username',
            'password' => 'testPassword123', // unmatched password
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['username or password wrong']
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $user = $this->getTestUser('test-username');
        $this->get('/api/users/current', [
            'Authorization' => $user->token
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'test-username',
                    'name' => 'Testing User'
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Unauthorized']
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $this->get('/api/users/current', [
            'Authorization' => 'token'
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Unauthorized']
                ]
            ]);
    }
}
