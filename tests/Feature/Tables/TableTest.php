<?php

namespace Tables;

use App\Helpers\Table\ExtendedTable;
use App\Livewire\Tables\ConfigurationItemsTable;
use App\Livewire\Tables\IncidentsTable;
use App\Livewire\Tables\Table;
use App\Livewire\Tables\TasksTable;
use App\Models\ConfigurationItem;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sets_a_visible_column_as_a_hidden_column_on_personalize_call()
    {
        $resolver = User::factory()->resolver()->create();
        $incidents = Incident::factory(3)->statusInProgress()->create();

        // Status column is visible by default
        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertSeeText('In Progress')
            ->set('selectedColumn', 'Status')
            ->call('setSelectedColumnHidden')
            ->call('personalize')
            ->assertRedirect()
            ->assertSessionHas('success', 'You have successfully personalized the table');

        Livewire::test(IncidentsTable::class)
            ->assertDontSeeText('In Progress');

        $this->assertDatabaseHas('table_personalizations', [
            'user_id' => $resolver->id,
            'table_name' => 'IncidentsTable',
            'columns' => 'Number,Description,Caller,Resolver,Priority,'
        ]);
    }

    /** @test */
    function it_sets_a_hidden_column_as_a_visible_column_on_personalize_call()
    {
        $resolver = User::factory()->resolver()->create();
        $incidents = Incident::factory(3, ['category_id' => IncidentCategory::COMPUTER])->create();

        // Category column is hidden by default
        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertDontSeeText('Computer')
            ->set('selectedColumn', 'Category')
            ->call('setSelectedColumnVisible')
            ->call('personalize')
            ->assertRedirect()
            ->assertSessionHas('success', 'You have successfully personalized the table');

        Livewire::test(IncidentsTable::class)
            ->assertSeeText('Computer');

        $this->assertDatabaseHas('table_personalizations', [
            'user_id' => $resolver->id,
            'table_name' => 'IncidentsTable',
            'columns' => 'Number,Description,Caller,Resolver,Status,Priority,Category,'
        ]);
    }

    /** @test */
    function it_does_not_add_invalid_column_to_visible_columns()
    {
        $resolver = User::factory()->resolver()->create();
        $incidents = Incident::factory(3)->create();

        // Category column is hidden by default
        Livewire::actingAs($resolver)
            ->test(IncidentsTable::class)
            ->assertDontSeeText('Computer')
            ->set('selectedColumn', 'Invalid Column')
            ->call('setSelectedColumnVisible')
            ->set('selectedColumn', 'Status')
            ->call('setSelectedColumnHidden')
            ->call('personalize')
            ->assertRedirect()
            ->assertSessionHas('success', 'You have successfully personalized the table');

        $this->assertDatabaseMissing('table_personalizations', [
            'user_id' => $resolver->id,
            'table_name' => 'IncidentsTable',
            'columns' => 'Number,Description,Caller,Resolver,Priority,Invalid Column'
        ]);

        $this->assertDatabaseHas('table_personalizations', [
            'user_id' => $resolver->id,
            'table_name' => 'IncidentsTable',
            'columns' => 'Number,Description,Caller,Resolver,Priority,'
        ]);
    }
}

