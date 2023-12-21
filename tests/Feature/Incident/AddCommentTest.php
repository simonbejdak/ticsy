<?php

namespace Tests\Feature\Incident;

use App\Livewire\Activities;
use App\Models\Comment;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Str;
use Tests\TestCase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_throws_403_to_user_who_has_not_created_the_incident()
    {
        $incident = Incident::factory()->create();
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Activities::class, ['model' => $incident])
            ->call('addComment', ['body' => 'Comment Body'])
            ->assertForbidden();
    }

    public function test_it_allows_to_add_comment_to_user_who_has_created_the_ticket()
    {
        $user = User::factory()->create();
        $incident = Incident::factory(['caller_id' => $user])->create();

        Livewire::actingAs($user)
            ->test(Activities::class, ['model' => $incident])
            ->set('body', 'Comment Body')
            ->call('addComment')
            ->assertSee('Comment Body');

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $incident->id,
            'causer_id' => $user->id,
            'event' => 'comment',
            'description' => 'Comment Body'
        ]);
    }

    public function test_it_allows_to_add_comment_to_resolver()
    {
        $resolver = User::factory()->resolver()->create();
        $incident = Incident::factory()->create();

        Livewire::actingAs($resolver)
            ->test(Activities::class, ['model' => $incident])
            ->set('body', 'Comment Body')
            ->call('addComment')
            ->assertSee('Comment Body');

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $incident->id,
            'causer_id' => $resolver->id,
            'event' => 'comment',
            'description' => 'Comment Body'
        ]);
    }

    public function test_it_fails_validation_with_empty_body()
    {
        $user = User::factory()->create();
        $incident = Incident::factory(['caller_id' => $user])->create();

        Livewire::actingAs($user)
            ->test(Activities::class, ['model' => $incident])
            ->set('body', '')
            ->call('addComment')
            ->assertHasErrors(['body' => 'required']);
    }


    public function test_it_fails_validation_with_body_having_more_characters_than_predefined()
    {
        $user = User::factory()->create();
        $incident = Incident::factory(['caller_id' => $user])->create();

        Livewire::actingAs($user)
            ->test(Activities::class, ['model' => $incident])
            ->set('body', Str::random(256))
            ->call('addComment')
            ->assertHasErrors(['body' => 'max']);
    }
}
