<?php

namespace Tests\Feature\User;

use App\Livewire\IncidentEditForm;
use App\Livewire\UserEditForm;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_redirects_guest_to_login_page()
    {
        $response = $this->get(route('users.edit', User::factory()->create()));
        $response->assertRedirectToRoute('login');
    }

    /** @test */
    function it_returns_forbidden_to_standard_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('users.edit', $user));

        $response->assertForbidden();
    }

    /** @test */
    function it_returns_successful_to_resolver()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('users.edit', $resolver));

        $response->assertSuccessful();
    }

    /** @test */
    function it_returns_successful_to_manager()
    {
        $manager = User::factory()->manager()->create();

        $this->actingAs($manager);
        $response = $this->get(route('users.edit', $manager));

        $response->assertSuccessful();
    }

    /** @test */
    function it_renders_specified_user_data()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('users.edit', $resolver));

        $response->assertSeeText($resolver->name);
        $response->assertSeeText($resolver->email);
        $response->assertSeeText($resolver->location->value);
        $response->assertSeeText($resolver->status->value);
        $response->assertSee($resolver->created_at->format('d.m.Y h:i:s'));
        $response->assertSee($resolver->updated_at->format('d.m.Y h:i:s'));
    }

    /** @test */
    function it_renders_created_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $user = User::factory()->create();

        Livewire::actingAs($resolver)
            ->test(UserEditForm::class, ['user' => $user])
            ->assertSuccessful()
            ->assertSeeInOrder([
                'Status:', 'Open',
                'Priority', '4',
                'Group:', 'SERVICE-DESK',
            ]);
    }

    /** @test */
    function it_renders_updated_activities()
    {
        //
    }

    /** @test */
    function it_allows_user_with_permission_update_users_to_update_specified_data()
    {
        //
    }
}
