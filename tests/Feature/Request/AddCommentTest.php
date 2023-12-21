<?php

namespace Tests\Feature\Request;

use App\Livewire\Activities;
use App\Models\Comment;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Str;
use Tests\TestCase;

class AddCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_throws_403_to_user_who_has_not_created_the_request()
    {
        $request = Request::factory()->create();
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Activities::class, ['model' => $request])
            ->call('addComment', ['body' => 'Test Comment',])
            ->assertForbidden();
    }

    public function test_it_allows_to_add_comment_to_user_who_has_created_the_request()
    {
        $caller = User::factory()->create();
        $request = Request::factory(['caller_id' => $caller])->create();

        Livewire::actingAs($caller)
            ->test(Activities::class, ['model' => $request])
            ->set('body', 'Comment Body')
            ->call('addComment')
            ->assertSee('Comment Body');

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $request->id,
            'causer_id' => $caller->id,
            'event' => 'comment',
            'description' => 'Comment Body'
        ]);
    }

    public function test_it_allows_to_add_comment_to_resolver()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($resolver)
            ->test(Activities::class, ['model' => $request])
            ->set('body', 'Comment Body')
            ->call('addComment')
            ->assertSee('Comment Body');

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $request->id,
            'causer_id' => $resolver->id,
            'event' => 'comment',
            'description' => 'Comment Body'
        ]);
    }

    public function test_it_fails_validation_with_empty_body()
    {
        $caller = User::factory()->create();
        $request = Request::factory(['caller_id' => $caller])->create();

        Livewire::actingAs($caller)
            ->test(Activities::class, ['model' => $request])
            ->set('body', '')
            ->call('addComment')
            ->assertHasErrors(['body' => 'required']);
    }


    public function test_it_fails_validation_with_body_having_more_characters_than_predefined()
    {
        $caller = User::factory()->create();
        $request = Request::factory(['caller_id' => $caller])->create();

        Livewire::actingAs($caller)
            ->test(Activities::class, ['model' => $request])
            ->set('body', Str::random(256))
            ->call('addComment')
            ->assertHasErrors(['body' => 'max']);
    }
}
