<?php


namespace Tests\Feature\Task;

use App\Enums\OnHoldReason;
use App\Enums\Priority;
use App\Enums\Status;
use App\Livewire\RequestEditForm;
use App\Livewire\TaskEditForm;
use App\Models\Group;
use App\Models\Request;
use App\Models\Task;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\TaskService;
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
        $task = Task::factory()->started()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));
        $response->assertSuccessful();
    }

    /** @test */
    function it_displays_task_data()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->started()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));

        $response->assertSuccessful();
        $response->assertSee($task->categoryName());
        $response->assertSee($task->itemName());
        $response->assertSee($task->group->name);
        $response->assertSee($task->status->value);
    }

    /** @test */
    function it_displays_comments()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->started()->create();

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
            ->set('comment', 'Test comment')
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
            ->set('comment', 'Production issue')
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
            ->set('comment', 'Production issue')
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
            ->test(TaskEditForm::class, ['task' => $task])
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
        $resolver = User::factory()->resolverAllGroups()->create();
        $task = Task::factory()->create();
        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::IN_PROGRESS->value)
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    function it_displays_multiple_activity_changes()
    {
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $resolver = User::factory()->resolverAllGroups()->create();
        $task = Task::factory([
            'status' => Status::OPEN,
        ])->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::IN_PROGRESS->value)
            ->set('group', $group->id)
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open'])
            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    function it_displays_status_changes_activity()
    {
        $resolver = User::factory()->resolverAllGroups()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::IN_PROGRESS->value)
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(TaskEditForm::class, ['task' => $task])
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
            ->set('comment', 'Test comment')
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(TaskEditForm::class, ['task' => $task])
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
            ->set('comment', 'Production issue')
            ->call('save')
            ->assertSuccessful();

        $task->refresh();

        Livewire::test(TaskEditForm::class, ['task' => $task])
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

        Livewire::test(TaskEditForm::class, ['task' => $task])
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

        Livewire::test(TaskEditForm::class, ['task' => $task])
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
            ->test(TaskEditForm::class, ['task' => $task])
            ->assertSeeInOrder([
                'Status:', 'Monitoring', 'was', 'In Progress',
                'Test Comment',
                'Status:', 'In Progress', 'was', 'Open',
                'Created', 'Status:', 'Open',
            ]);
    }

    /** @test */
    function it_requires_comment_if_priority_changes()
    {
        $task = Task::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(TaskEditForm::class, ['task' => $task])
            ->set('priority', 3)
            ->call('save')
            ->assertHasErrors(['comment' => 'required'])
            ->set('comment', 'Production issue')
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

    /** @test */
    function it_returns_forbidden_if_task_is_not_started(){
        $task = Task::factory()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));

        $this->assertFalse($task->isStarted());
        $response->assertForbidden();
    }

    /** @test */
    function it_returns_successful_if_task_is_started(){
        $task = Task::factory(['started_at' => Carbon::now()])->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));

        $this->assertTrue($task->isStarted());
        $response->assertSuccessful();
    }

    /** @test */
    function resolver_is_required_if_status_in_progress()
    {
        $task = Task::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('status', Status::IN_PROGRESS->value)
            ->call('save')
            ->assertHasErrors(['resolver' => 'required']);
    }

    /** @test */
    function it_loads_correctly_when_task_has_taskable(){
        $task = Request::factory()->create()->tasks()->first();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));
        $response->assertSuccessful();
    }

    /** @test */
    function it_loads_correctly_when_task_does_not_have_taskable(){
        $task = Task::factory()->started()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));
        $response->assertSuccessful();
    }

    /** @test */
    function resolver_set_to_null_if_status_is_open()
    {
        $resolver = User::factory()->resolverAllGroups()->create();
        $task = Task::factory(['resolver_id' => $resolver])->statusInProgress()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->assertSet('resolver', $resolver->id)
            ->set('status', Status::OPEN->value)
            ->assertSet('resolver', null);
    }

    /** @test */
    function it_does_not_render_empty_activity_after_started_at_attribute_is_updated(){
        $task = Task::factory()->create();
        $resolver = User::factory()->resolver()->create();

        TaskService::startTask($task);

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('status' , Status::MONITORING->value)
            ->call('save')
            ->assertSuccessful();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->assertDontSeeText('Updated &bullet;');
    }

    /** @test */
    function it_successfully_loads_if_task_status_is_on_hold(){
        $task = Task::factory()->started()->statusOnHold()->create();
        $resolver = User::factory()->resolver()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('tasks.edit', $task));
        $response->assertSuccessful();
    }

    /** @test */
    function it_does_not_require_comment_if_status_is_on_hold_and_status_was_not_changed()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->statusOnHold()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->call('save')
            ->assertHasNoErrors(['comment', 'required']);
    }

    /** @test */
    function it_does_not_require_comment_if_status_is_resolved_and_status_was_not_changed()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->statusResolved()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->call('save')
            ->assertHasNoErrors(['comment', 'required']);
    }

    /** @test */
    function it_does_not_require_comment_if_status_is_cancelled_and_status_was_not_changed()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->statusCancelled()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->call('save')
            ->assertHasNoErrors(['comment', 'required']);
    }

    /** @test */
    function resolver_cannot_change_priority()
    {
        $resolver = User::factory()->resolver()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($resolver)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('priority', Priority::THREE->value)
            ->assertForbidden();
    }

    /** @test */
    function manager_can_change_priority()
    {
        $manager = User::factory()->manager()->create();
        $task = Task::factory()->create();

        Livewire::actingAs($manager)
            ->test(TaskEditForm::class, ['task' => $task])
            ->set('priority', Priority::THREE->value)
            ->set('comment', 'Production Issue')
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'priority' => Priority::THREE->value,
        ]);
    }
}
