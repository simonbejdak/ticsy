<?php

namespace Tables;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DuskTestCase;

class TableTest extends DuskTestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sets_a_visible_column_as_a_hidden_column_on_personalize_call()
    {
        //
    }

    /** @test */
    function it_sets_a_hidden_column_as_a_visible_column_on_personalize_call()
    {
        //
    }

    /** @test */
    function it_does_not_add_invalid_column_to_visible_columns()
    {
        //
    }

    /** @test */
    function it_moves_selected_visible_column_up_if_up_button_is_clicked_and_its_position_is_greater_than_zero()
    {
        //
    }

    /** @test */
    function it_does_not_move_selected_visible_column_up_if_up_button_is_clicked_and_its_position_is_lower_or_equal_to_zero()
    {
        //
    }

    /** @test */
    function it_moves_selected_visible_column_down_if_down_button_is_clicked_and_its_position_is_lower_than_visible_columns_count()
    {
        //
    }

    /** @test */
    function it_does_not_move_selected_visible_column_up_if_down_button_is_clicked_and_its_position_is_greater_or_equal_to_visible_columns_count()
    {
        //
    }

    /** @test */
    function it_does_not_save_personalization_if_set_visible_columns_are_invalid()
    {
        //
    }
}

