<?php

namespace Livewire;

use App\Livewire\Activities;
use App\Models\Incident;
use App\Models\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class ActivitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_throws_403_to_standard_user()
    {
        $user = User::factory()->create();

        foreach ($this->models() as $model){
            Livewire::actingAs($user)
                ->test(Activities::class, ['model' => $model])
                ->call('addComment', ['body' => 'Test Comment',])
                ->assertForbidden();
        }
    }

    /** @test */
    function it_allows_to_add_comment_to_resolver()
    {
        $resolver = User::factory()->resolver()->create();

        foreach ($this->models() as $model) {
            Livewire::actingAs($resolver)
                ->test(Activities::class, ['model' => $model])
                ->set('body', 'Comment Body')
                ->call('addComment')
                ->assertSee('Comment Body');

            $this->assertDatabaseHas('activity_log', [
                'subject_id' => $model->id,
                'causer_id' => $resolver->id,
                'event' => 'comment',
                'description' => 'Comment Body'
            ]);
        }
    }

    /** @test */
    function it_fails_validation_with_empty_body()
    {
        $resolver = User::factory()->resolver()->create();

        foreach ($this->models() as $model){
            Livewire::actingAs($resolver)
                ->test(Activities::class, ['model' => $model])
                ->set('body', '')
                ->call('addComment')
                ->assertHasErrors(['body' => 'required']);
        }
    }


    /** @test  */
    function it_fails_validation_with_body_having_more_characters_than_255()
    {
        $resolver = User::factory()->resolver()->create();

        foreach ($this->models() as $model){
            Livewire::actingAs($resolver)
                ->test(Activities::class, ['model' => $model])
                ->set('body', Str::random(256))
                ->call('addComment')
                ->assertHasErrors(['body' => 'max']);
        }
    }

    // Factories are not working with DataProviders well
    protected function models(): array
    {
        return [
            Incident::factory()->create(),
            Request::factory()->create(),
            Task::factory()->create()
        ];
    }
}
