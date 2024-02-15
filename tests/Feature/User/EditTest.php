<?php

namespace Tests\Feature\User;

use App\Enums\Location;
use App\Enums\UserStatus;
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
        $user = User::factory([
            'name' => 'Average Joe',
            'email' => 'average.joe@gmail.com',
            'location' => Location::MICHIGAN,
            'status' => UserStatus::ACTIVE,
        ])->create();

        Livewire::actingAs($resolver)
            ->test(UserEditForm::class, ['user' => $user])
            ->assertSuccessful()
            ->assertSeeInOrder([
                'Name:', 'Average Joe',
                'Email', 'average.joe@gmail.com',
                'Location', 'Michigan',
                'Status', 'Active',
            ]);
    }

    /** @test */
    function it_renders_updated_activities()
    {
        $resolver = User::factory()->resolver()->create();
        $user = User::factory([
            'name' => 'Average Joe',
            'email' => 'average.joe@gmail.com',
            'location' => Location::MICHIGAN,
            'status' => UserStatus::ACTIVE,
        ])->create();

        $user->name = 'John Doe';
        $user->email = 'john.doe@gmail.com';
        $user->location = Location::DOLNY_KUBIN;
        $user->status = UserStatus::INACTIVE;
        $user->save();
        $user->refresh();

        Livewire::actingAs($resolver)
            ->test(UserEditForm::class, ['user' => $user])
            ->assertSuccessful()
            ->assertSeeInOrder([
                'Updated',
                'Name:', 'John Doe',
                'Email:', 'john.doe@gmail.com',
                'Location:', 'Dolný Kubín',
                'Status:', 'Inactive',
            ]);
    }

    /** @test */
    function it_allows_resolver_to_update_specified_data()
    {
        $resolver = User::factory()->resolver()->create();
        $user = User::factory([
            'location' => Location::DOLNY_KUBIN,
            'status' => UserStatus::ACTIVE,
        ])->create();

        Livewire::actingAs($resolver)
            ->test(UserEditForm::class, ['user' => $user])
            ->set('location', Location::NAMESTOVO->value)
            ->set('status', UserStatus::INACTIVE->value)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'location' => Location::NAMESTOVO->value,
            'status' => UserStatus::INACTIVE->value,
        ]);
    }
}
