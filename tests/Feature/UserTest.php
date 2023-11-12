<?php

namespace Tests\Feature;

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
}
