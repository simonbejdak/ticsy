<?php

namespace ResolverPanel;

use App\Enums\Priority;
use App\Enums\Tab;
use App\Helpers\Table\Table;
use App\Livewire\Tables\IncidentsTable;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResolverPanelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_does_not_render_to_guest()
    {

    }

    /** @test */
    function it_does_not_render_to_standard_user()
    {

    }

    /** @test */
    function it_renders_to_resolver()
    {

    }

    /** @test */
    function it_renders_to_manager(){

    }

    /** @test */
    function it_lists_all_options_if_selected_tab_is_all()
    {

    }

    /** @test */
    function it_lists_user_favorite_options_if_selected_tab_is_favorites()
    {

    }

    /** @test */
    function it_marks_incidents_option_as_selected_if_route_matches_the_option_route()
    {

    }

    /** @test */
    function star_is_visible_if_option_is_marked_as_favorite()
    {

    }

    /** @test */
    function it_removes_favorite_option_from_panel_if_it_is_unmarked_as_selected()
    {

    }

    /** @test */
    function it_persists_selected_tab_after_page_reload()
    {

    }

    /** @test */
    function it_persists_favorite_options_after_page_reload()
    {

    }
}

