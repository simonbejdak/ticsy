<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestUserSeesCorrectAuthMenu()
    {
        $response = $this->get(route('home'));

        $response->assertSee('Login');
        $response->assertSee('Register');
        $response->assertDontSee('Logout');
    }

    public function testAuthUserSeesCorrectAuthMenu()
    {
        $this->actingAs(User::factory([
            'name' => 'Šimon Bejdák',
        ])->create());

        $response = $this->get(route('home'));

        $response->assertSee('Šimon Bejdák');
        $response->assertSee('Logout');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }
}
