<?php


namespace Tests\Feature\Task;

use App\Livewire\Activities;
use App\Livewire\RequestEditForm;
use App\Livewire\TaskEditForm;
use App\Models\Group;
use App\Enums\OnHoldReason;
use App\Models\Task;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Enums\Status;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_redirect_guests_to_login_page()
    {
        $response = $this->get(route('tasks.edit', Task::factory()->create()));

        $response->assertRedirectToRoute('login');
    }

    /** @test */
    function it_errors_to_403_to_unauthorized_users()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('tasks.edit', Task::factory()->create()));

        $response->assertForbidden();
    }

    /** @test */
    function it_errors_to_403_to_caller(){
        $task = Task::factory()->create();
        $caller = $task->caller;

        $this->actingAs($caller);
        $response = $this->get(route('tasks.edit', $task));

        $response->assertForbidden();
    }

    /** @test */
    function it_authorizes_resolver_to_view(){
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));
        $response->assertSuccessful();
    }

    /** @test */
    function it_displays_task_data()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));

        $response->assertSuccessful();
        $response->assertSee($task->category->name);
        $response->assertSee($task->item->name);
        $response->assertSee($task->group->name);
        $response->assertSee($task->status->value);
    }

    /** @test */
    function it_displays_comments()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        $this->actingAs($resolver);
        ActivityService::comment($task, 'Comment Body');

        $response = $this->get(route('tasks.edit', $task));

        $response->assertSuccessful();
        $response->assertSee('Comment Body');
    }

    /** @test */
    function on_hold_reason_field_is_hidden_when_status_is_not_on_hold()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->statusInProgress()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->assertDontSeeHtml('> On hold reason </label>');
    }

    /** @test */
    function on_hold_reason_field_is_shown_when_status_is_on_hold()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory(['on_hold_reason' => OnHoldReason::CALLER_RESPONSE])->statusOnHold()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->assertSee('On hold reason');
    }

    /** @test */
    function status_can_be_set_to_cancelled_if_previous_status_is_different()
    {
        $task = Task::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::CANCELLED->value)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => Status::CANCELLED,
        ]);
    }

    /** @test */
    function it_returns_forbidden_if_user_with_no_permission_sets_priority_one_to_a_task()
    {
        // resolver does not have a permisssion to set priority one
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('priority', 1)
            ->assertForbidden();
    }

    /** @test */
    function it_does_not_return_forbidden_if_user_with_permission_assigns_priority_one_to_a_request()
    {
        // manager has a permisssion to set priority one
        $manager = User::factory()->manager()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($manager)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('priority', 1)
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tasks', [
           'id' => $task->id,
           'priority' => 1,
        ]);
    }

    /** @test */
    function it_allows_user_with_permission_to_set_priority_one_to_also_set_lower_priorities()
    {
        // manager has a permisssion to set priority one
        $manager = User::factory()->manager()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($manager)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('priority', 2)
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'priority' => 2,
        ]);
    }

    /** @test */
    function it_displays_task_created_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver)
            ->test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder([
                'Status:', 'Open',
                'Priority', '4',
                'Group:', 'SERVICE-DESK',
            ]);
    }

    /** @test */
    function it_displays_changes_activity_dynamically()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();
        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::IN_PROGRESS->value)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    function it_displays_multiple_activity_changes()
    {
        $resolver = User::factory()->resolver()->create();
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $task = Task::factory([
            'status' => Status::OPEN,
        ])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::IN_PROGRESS->value)
            ->set('group', $group->id)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open'])
            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    function it_displays_status_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::IN_PROGRESS->value)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    function it_displays_on_hold_reason_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::ON_HOLD->value)
            ->set('onHoldReason', OnHoldReason::CALLER_RESPONSE->value)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['On hold reason:', 'Caller Response', 'was', 'empty']);
    }

    /** @test */
    function it_displays_priority_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory(['priority' => Task::DEFAULT_PRIORITY])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('priority', 3)
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Priority:', '3', 'was', Task::DEFAULT_PRIORITY]);
    }

    /** @test */
    function it_displays_group_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('group', $group->id)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    function it_displays_resolver_changes_activity()
    {
        $resolver = User::factory(['name' => 'Average Joe'])->resolverAllGroups()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(Activities::class, ['model' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Resolver:', 'Average Joe', 'was', 'empty']);
    }

    /** @test */
    function it_displays_activities_in_descending_order()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        $task->status = Status::IN_PROGRESS;
        $task->save();

        ActivityService::comment($task, 'Test Comment');

        $task->status = Status::MONITORING;
        $task->save();

        $task->refresh();

        Livewire::actingAs($resolver)
            ->test(Activities::class, ['model' => $task])
            ->assertSeeInOrder([
                'Status:', 'Monitoring', 'was', 'In Progress',
                'Test Comment',
                'Status:', 'In Progress', 'was', 'Open',
                'Created', 'Status:', 'Open',
            ]);
    }

    /** @test */
    function it_requires_priority_change_reason_if_priority_changes()
    {
        $task = Task::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('priority', 3)
            ->call('save')
            ->assertHasErrors(['priorityChangeReason' => 'required'])
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();
    }

    /** @test */
    function sla_bar_shows_correct_minutes()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        $date = Carbon::now()->addMinutes(10);
        Carbon::setTestNow($date);

        Livewire::actingAs($resolver)
            ->test(taskEditForm::class, ['task' => $task])
            ->assertSee($task->sla->minutesTillExpires() . ' minutes');
    }
}
