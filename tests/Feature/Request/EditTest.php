<?php


namespace Tests\Feature\Request;

use App\Enums\OnHoldReason;
use App\Enums\Status;
use App\Enums\Tab;
use App\Livewire\Activities;
use App\Livewire\RequestEditForm;
use App\Livewire\Tabs;
use App\Livewire\Tasks;
use App\Models\Group;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
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
        $response = $this->get(route('requests.edit', Request::factory()->create()));

        $response->assertRedirectToRoute('login');
    }

    /** @test */
    function it_errors_to_403_to_unauthorized_users()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('requests.edit', Request::factory()->create()));

        $response->assertForbidden();
    }

    /** @test */
    function it_authorizes_caller_to_view(){
        $caller = User::factory()->create();
        $request = Request::factory(['caller_id' => $caller])->create();

        $this->actingAs($caller);
        $response = $this->get(route('requests.edit', $request));
        $response->assertSuccessful();
    }

    /** @test */
    function it_authorizes_resolver_to_view(){
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->create();

        $this->actingAs($resolver);
        $response = $this->get(route('requests.edit', $request));
        $response->assertSuccessful();
    }

    /** @test */
    public function it_displays_request_data()
    {
        $category = RequestCategory::firstOrFail();
        $item = RequestItem::firstOrFail();
        $group = Group::firstOrFail();
        $status = Status::OPEN;

        $caller = User::factory()->create();
        $resolver = User::factory(['name' => 'John Doe'])->resolverAllGroups()->create();
        $request = Request::factory([
            'category_id' => $category,
            'item_id' => $item,
            'group_id' => $group,
            'resolver_id' => $resolver,
            'status' => $status,
            'caller_id' => $caller,
        ])->create();

        $this->actingAs($caller);

        $response = $this->get(route('requests.edit', $request));
        $response->assertSuccessful();
        $response->assertSee($category->name);
        $response->assertSee($item->name);
        $response->assertSee($group->name);
        $response->assertSee($resolver->name);
        $response->assertSee($status->value);
    }

    /** @test */
    public function it_displays_comments()
    {
        $caller = User::factory()->create();
        $request = Request::factory(['caller_id' => $caller])->create();

        $this->actingAs($caller);
        ActivityService::comment($request, 'Comment Body');

        $response = $this->get(route('requests.edit', $request));

        $response->assertSuccessful();
        $response->assertSee('Comment Body');
    }

    /** @test */
    public function on_hold_reason_field_is_hidden_when_status_is_not_on_hold()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->statusInProgress()->create();

        Livewire::actingAs($resolver)
            ->test(RequestEditForm::class, ['request' => $request])
            ->assertDontSeeHtml('> On hold reason </label>');
    }

    /** @test */
    public function on_hold_reason_field_is_shown_when_status_is_on_hold()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory(['on_hold_reason' => OnHoldReason::CALLER_RESPONSE])->statusOnHold()->create();

        Livewire::actingAs($resolver)
            ->test(RequestEditForm::class, ['request' => $request])
            ->assertSee('On hold reason');
    }

    /** @test */
    public function status_can_be_set_to_cancelled_if_previous_status_is_different()
    {
        $request = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(RequestEditForm::class, ['request' => $request])
            ->set('status', Status::CANCELLED->value)
            ->set('comment', 'Test comment')
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => Status::CANCELLED,
        ]);
    }

    /** @test */
    public function it_returns_forbidden_if_user_with_no_permission_sets_priority_one_to_a_request()
    {
        // resolver does not have a permisssion to set priority one
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($resolver)
            ->test(RequestEditForm::class, ['request' => $request])
            ->set('priority', 1)
            ->assertForbidden();
    }

    /** @test */
    public function it_does_not_return_forbidden_if_user_with_permission_assigns_priority_one_to_a_request()
    {
        // manager has a permisssion to set priority one
        $manager = User::factory()->manager()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($manager)
            ->test(RequestEditForm::class, ['request' => $request])
            ->set('priority', 1)
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('requests', [
           'id' => $request->id,
           'priority' => 1,
        ]);
    }

    /** @test */
    public function it_allows_user_with_permission_to_set_priority_one_to_also_set_lower_priorities()
    {
        // manager has a permisssion to set priority one
        $manager = User::factory()->manager()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($manager)
            ->test(RequestEditForm::class, ['request' => $request])
            ->set('priority', 2)
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'priority' => 2,
        ]);
    }

    /** @test */
    public function it_displays_request_created_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($resolver)
            ->test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder([
                'Status:', 'Open',
                'Priority', '4',
                'Group:', 'SERVICE-DESK',
            ]);
    }

    /** @test */
    public function it_displays_changes_activity_dynamically()
    {
        $resolver = User::factory()->resolverAllGroups()->create();
        $request = Request::factory()->create();
        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('status', Status::IN_PROGRESS->value)
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $request->refresh();

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    public function it_displays_multiple_activity_changes()
    {
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $resolver = User::factory()->resolverAllGroups()->create();
        $request = Request::factory([
            'status' => Status::OPEN,
        ])->create();

        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('status', Status::IN_PROGRESS->value)
            ->set('group', $group->id)
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $request->refresh();

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open'])
            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    public function it_displays_status_changes_activity()
    {
        $resolver = User::factory()->resolverAllGroups()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('status', Status::IN_PROGRESS->value)
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $request->refresh();

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    public function it_displays_on_hold_reason_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('status', Status::ON_HOLD->value)
            ->set('onHoldReason', OnHoldReason::CALLER_RESPONSE->value)
            ->set('comment', 'Test comment')
            ->call('save')
            ->assertSuccessful();

        $request->refresh();

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder(['On hold reason:', 'Caller Response', 'was', 'empty']);
    }

    /** @test */
    public function it_displays_priority_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory(['priority' => Request::DEFAULT_PRIORITY])->create();

        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('priority', 3)
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();

        $request->refresh();

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder(['Priority:', '3', 'was', Request::DEFAULT_PRIORITY]);
    }

    /** @test */
    public function it_displays_group_changes_activity()
    {
        $resolver = User::factory()->resolver()->create();
        $group = Group::factory(['name' => 'TEST-GROUP'])->create();
        $request = Request::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('group', $group->id)
            ->call('save')
            ->assertSuccessful();

        $request->refresh();

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder(['Group:', 'TEST-GROUP', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    public function it_displays_resolver_changes_activity()
    {
        $resolver = User::factory(['name' => 'Average Joe'])->resolverAllGroups()->create();
        $request = Request::factory()->create();

        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('resolver', $resolver->id)
            ->call('save')
            ->assertSuccessful();

        $request->refresh();

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSuccessful()
            ->assertSeeInOrder(['Resolver:', 'Average Joe', 'was', 'empty']);
    }

    /** @test */
    public function it_displays_activities_in_descending_order()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->create();

        $request->status = Status::IN_PROGRESS;
        $request->save();

        ActivityService::comment($request, 'Test Comment');

        $request->status = Status::MONITORING;
        $request->save();

        $request->refresh();

        Livewire::actingAs($resolver)
            ->test(Activities::class, ['model' => $request])
            ->assertSeeInOrder([
                'Status:', 'Monitoring', 'was', 'In Progress',
                'Test Comment',
                'Status:', 'In Progress', 'was', 'Open',
                'Created', 'Status:', 'Open',
            ]);
    }

    /** @test */
    public function it_requires_priority_change_reason_if_priority_changes()
    {
        $request = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('priority', 3)
            ->call('save')
            ->assertHasErrors(['priorityChangeReason' => 'required'])
            ->set('priorityChangeReason', 'Production issue')
            ->call('save')
            ->assertSuccessful();
    }

    /** @test */
    public function sla_bar_shows_correct_minutes()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory()->create();

        $date = Carbon::now()->addMinutes(10);
        Carbon::setTestNow($date);

        Livewire::actingAs($resolver)
            ->test(RequestEditForm::class, ['request' => $request])
            ->assertSee($request->sla->minutesTillExpires() . ' minutes');
    }

    public function test_it_allows_to_add_comment_to_user_who_has_created_the_request()
    {
        $caller = User::factory()->create();
        $request = Request::factory(['caller_id' => $caller])->create();

        Livewire::actingAs($caller);

        Livewire::test(RequestEditForm::class, ['request' => $request])
            ->set('comment', 'Test comment')
            ->call('save');

        Livewire::test(Activities::class, ['model' => $request])
            ->assertSee('Test comment');

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $request->id,
            'causer_id' => $caller->id,
            'event' => 'comment',
            'description' => 'Test comment'
        ]);
    }

    /** @test */
    function it_shows_all_tasks_if_task_sequence_is_at_once(){
        $request = Request::factory()->taskSequenceAtOnce()->create();
        $resolver = User::factory()->create();
        $tasks = $request->tasks;

        Livewire::actingAs($resolver);

        foreach ($tasks as $task){
            Livewire::test(Tabs::class, ['tabs' => [Tab::ACTIVITIES, Tab::TASKS], 'model' => $request])
                ->call('setViewedTab', 'tasks')
                ->assertSee($task->description);
        }
    }

    /** @test */
    function it_shows_only_started_tasks_if_task_sequence_is_gradient(){
        $request = Request::factory()->taskSequenceGradient()->create();
        $resolver = User::factory()->create();
        $startedTasks = $request->tasks()->started()->get();
        $notStartedTasks = $request->tasks()->notStarted()->get();


        Livewire::actingAs($resolver);

        foreach ($startedTasks as $task){
            Livewire::test(Tabs::class, ['tabs' => [Tab::ACTIVITIES, Tab::TASKS], 'model' => $request])
                ->call('setViewedTab', 'tasks')
                ->assertSee($task->description);
        }

        foreach ($notStartedTasks as $task){
            Livewire::test(Tabs::class, ['tabs' => [Tab::ACTIVITIES, Tab::TASKS], 'model' => $request])
                ->call('setViewedTab', 'tasks')
                ->assertDontSee($task->description);
        }
    }

    /** @test */
    function it__shows_only_started_tasks(){
        $request = Request::factory()->taskSequenceGradient()->create();
        $resolver = User::factory()->resolver()->create();
        $startedTasks = $request->tasks()->started()->get();
        $notStartedTasks = $request->tasks()->notStarted()->get();

        Livewire::actingAs($resolver);

        foreach ($startedTasks as $startedTask){
            $this->assertTrue($startedTask->isStarted());
            Livewire::test(Tasks::class, ['model' => $request])
                ->assertSee($startedTask->description);
        }

        foreach ($notStartedTasks as $notStartedTask){
            $this->assertFalse($notStartedTask->isStarted());
            Livewire::test(Tasks::class, ['model' => $request])
                ->assertDontSee($notStartedTask->description);
        }
    }

    /** @test */
    function resolver_is_required_if_status_in_progress()
    {
        $request = Request::factory()->create();
        $resolver = User::factory()->resolver()->create();

        Livewire::actingAs($resolver)
            ->test(RequestEditForm::class, ['request' => $request])
            ->set('status', Status::IN_PROGRESS->value)
            ->call('save')
            ->assertHasErrors(['resolver' => 'required']);
    }

    /** @test */
    function resolver_set_to_null_if_status_is_open()
    {
        $resolver = User::factory()->resolverAllGroups()->create();
        $request = Request::factory(['resolver_id' => $resolver])->statusInProgress()->create();

        Livewire::actingAs($resolver)
            ->test(RequestEditForm::class, ['request' => $request])
            ->assertSet('resolver', $resolver->id)
            ->set('status', Status::OPEN->value)
            ->assertSet('resolver', null);
    }
}
