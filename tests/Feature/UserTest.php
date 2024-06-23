<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_can_access_signup_page(): void
    {
        $response = $this->get('/signup');

        $response->assertStatus(200);
    }

    public function test_no_users_exist(): void
    {
        $this->assertEmpty(User::all());
    }

    public function test_can_signup_new_user(): void
    {
        $response = $this->post('/signup', [
            'first-name' => 'John',
            'last-name' => 'Doe',
            'email' => 'johndoe@virginia.edu',
            'phone' => '3095557382',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/email/verify');
    }
}
