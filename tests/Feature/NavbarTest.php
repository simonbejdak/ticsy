<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_correct_menu_to_guest_user()
    {
        $response = $this->get(route('home'));

        $response->assertSee('Login');
        $response->assertSee('Register');
        $response->assertDontSee('Logout');
    }

    public function test_it_shows_correct_menu_to_auth_user()
    {
        $this->actingAs(User::factory([
            'name' => 'John Doe',
        ])->create());

        $response = $this->get(route('home'));

        $response->assertSee('John Doe');
        $response->assertSee('Logout');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }
}
