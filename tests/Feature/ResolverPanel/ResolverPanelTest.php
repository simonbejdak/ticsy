<?php

namespace ResolverPanel;

use App\Enums\ResolverPanelOption;
use App\Enums\ResolverPanelTab;
use App\Livewire\ResolverPanel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResolverPanelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_does_not_render_to_guest()
    {
        $response = $this->get(route('home'));

        $response->assertDontSeeLivewire(ResolverPanel::class);
    }

    /** @test */
    function it_does_not_render_to_standard_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('home'));

        $response->assertDontSeeLivewire(ResolverPanel::class);
    }

    /** @test */
    function it_renders_to_resolver()
    {
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('home'));

        $response->assertSeeLivewire(ResolverPanel::class);
    }

    /** @test */
    function it_renders_to_manager(){
        $manager = User::factory()->manager()->create();

        $this->actingAs($manager);
        $response = $this->get(route('home'));
        $response->assertSeeLivewire(ResolverPanel::class);


    }

    /** @test */
    function it_lists_all_options_if_selected_tab_is_all()
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(ResolverPanel::class)
            ->call('allTabClicked')
            ->assertSeeInOrder(ResolverPanelOption::cases());
    }

    /** @test */
    function it_lists_user_favorite_options_if_selected_tab_is_favorites()
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(\App\Livewire\ResolverPanelOption::class, ['option' => ResolverPanelOption::INCIDENTS])
            ->call('starClicked');

        Livewire::test(ResolverPanel::class)
            ->call('favoritesTabClicked')
            ->assertSee(ResolverPanelOption::INCIDENTS->value);
    }

    /** @test */
    function it_removes_favorite_option_from_panel_if_it_is_unmarked_as_selected()
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(\App\Livewire\ResolverPanelOption::class, ['option' => ResolverPanelOption::INCIDENTS])
            ->call('starClicked')
            ->call('starClicked');

        Livewire::test(ResolverPanel::class)
            ->call('favoritesTabClicked')
            ->assertDontSee(ResolverPanelOption::INCIDENTS->value);
    }

    /** @test */
    function it_persists_selected_tab_after_livewire_reload()
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(ResolverPanel::class)
            ->call('allTabClicked')
            ->assertSet('selectedTab', ResolverPanelTab::ALL)
            ->call('favoritesTabClicked')
            ->assertSet('selectedTab', ResolverPanelTab::FAVORITES);

        Livewire::test(ResolverPanel::class)
            ->assertSet('selectedTab', ResolverPanelTab::FAVORITES);
    }

    /** @test */
    function it_persists_selected_resolver_panel_tab_in_database()
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(ResolverPanel::class)
            ->call('favoritesTabClicked');

        $this->assertDatabaseHas('users', [
            'id' => $resolver->id,
            'selected_resolver_panel_tab' => ResolverPanelTab::FAVORITES->value
        ]);

        Livewire::test(ResolverPanel::class)
            ->call('allTabClicked');

        $this->assertDatabaseHas('users', [
            'id' => $resolver->id,
            'selected_resolver_panel_tab' => ResolverPanelTab::ALL->value
        ]);
    }

    /** @test */
    function it_persists_favorite_options_after_page_reload()
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(\App\Livewire\ResolverPanelOption::class, ['option' => ResolverPanelOption::INCIDENTS])
            ->assertSet('favorite', false)
            ->call('starClicked');

        Livewire::test(\App\Livewire\ResolverPanelOption::class, ['option' => ResolverPanelOption::INCIDENTS])
            ->assertSet('favorite', true);
    }

    /** @test */
    function it_persists_favorite_options_in_database()
    {
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(\App\Livewire\ResolverPanelOption::class, ['option' => ResolverPanelOption::INCIDENTS])
            ->call('starClicked');

        $this->assertDatabaseHas('favorite_resolver_panel_options', [
            'user_id' => $resolver->id,
            'option' => ResolverPanelOption::INCIDENTS->value
        ]);

        Livewire::actingAs($resolver)
            ->test(\App\Livewire\ResolverPanelOption::class, ['option' => ResolverPanelOption::INCIDENTS])
            ->call('starClicked');

        $this->assertDatabaseMissing('favorite_resolver_panel_options', [
            'user_id' => $resolver->id,
            'option' => ResolverPanelOption::INCIDENTS->value
        ]);
    }
}

