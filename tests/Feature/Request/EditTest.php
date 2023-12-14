<?php


namespace Tests\Feature\Request;

use App\Livewire\Activities;
use App\Livewire\RequestEditForm;
use App\Livewire\TicketEditForm;
use App\Models\Category;
use App\Models\Group;
use App\Models\Item;
use App\Models\OnHoldReason;
use App\Models\RequestCategory;
use App\Models\RequestItem;
use App\Models\RequestOnHoldReason;
use App\Models\RequestStatus;
use App\Models\Status;
use App\Models\Request;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\TicketService;
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
        $status = RequestStatus::firstOrFail();

        $caller = User::factory()->create();
        $resolver = User::factory(['name' => 'John Doe'])->resolverAllGroups()->create();
        $request = Request::factory([
            'category_id' => $category,
            'item_id' => $item,
            'group_id' => $group,
            'resolver_id' => $resolver,
            'status_id' => $status,
            'caller_id' => $caller,
        ])->create();


        $this->actingAs($caller);

        $response = $this->get(route('requests.edit', $request));
        $response->assertSuccessful();
        $response->assertSee($category->name);
        $response->assertSee($item->name);
        $response->assertSee($group->name);
        $response->assertSee($resolver->name);
        $response->assertSee($status->name);
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
            ->assertDontSee('On hold reason');
    }

    /** @test */
    public function on_hold_reason_field_is_shown_when_status_is_on_hold()
    {
        $resolver = User::factory()->resolver()->create();
        $request = Request::factory(['on_hold_reason_id' => RequestOnHoldReason::CALLER_RESPONSE])->statusOnHold()->create();

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
            ->set('status', RequestStatus::CANCELLED)
            ->call('save')
            ->assertSuccessful();

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status_id' => RequestStatus::CANCELLED,
        ]);
    }

    /** @test */
    public function it_returns_forbidden_if_user_with_no_permission_sets_priority_one_to_a_ticket()
    {
//        // resolver does not have a permisssion to set priority one
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('priority', 1)
//            ->assertForbidden();
    }

    /** @test */
    public function it_does_not_return_forbidden_if_user_with_permission_assigns_priority_one_to_a_ticket()
    {
//        // manager has a permisssion to set priority one
//        $user = User::factory()->manager()->create();
//        $ticket = Ticket::factory()->create();
//
//        Livewire::actingAs($user)
//            ->test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('priority', 1)
//            ->set('priorityChangeReason', 'Production issue')
//            ->call('save')
//            ->assertSuccessful();
//
//        $this->assertDatabaseHas('tickets', [
//           'id' => $ticket->id,
//           'priority' => 1,
//        ]);
    }

    /** @test */
    public function it_allows_user_with_permission_to_set_priority_one_to_also_set_lower_priorities()
    {
//        // manager has a permisssion to set priority one
//        $user = User::factory()->manager()->create();
//        $ticket = Ticket::factory()->create();
//
//        Livewire::actingAs($user)
//            ->test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('priority', 2)
//            ->set('priorityChangeReason', 'Production issue')
//            ->call('save')
//            ->assertSuccessful();
//
//        $this->assertDatabaseHas('tickets', [
//            'id' => $ticket->id,
//            'priority' => 2,
//        ]);
    }

    /** @test */
    public function it_emits_ticket_updated_on_save_call()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(TicketEditForm::class, ['ticket' => $ticket])
//            ->call('save')
//            ->assertDispatched('ticket-updated');
    }

    /** @test */
    public function it_displays_ticket_created_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory()->create();
//
//        Livewire::actingAs($resolver)
//            ->test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder([
//                'Status:', 'Open',
//                'Priority', '4',
//                'Group:', 'SERVICE-DESK',
//            ]);
    }

    /** @test */
    public function it_displays_changes_activity_dynamically()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory(['status_id' => Status::OPEN])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('status', Status::IN_PROGRESS)
//            ->call('save')
//            ->assertSuccessful();
//
//        $ticket = $ticket->refresh();
//
//        Livewire::test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    public function it_displays_multiple_activity_changes()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory([
//            'status_id' => Status::OPEN,
//            'group_id' => Group::SERVICE_DESK,
//        ])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('status', Status::IN_PROGRESS)
//            ->set('group', Group::LOCAL_6445_NEW_YORK)
//            ->call('save')
//            ->assertSuccessful();
//
//        $ticket = $ticket->refresh();
//
//        Livewire::test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open'])
//            ->assertSeeInOrder(['Group:', 'LOCAL-6445-NEW-YORK', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    public function it_displays_status_changes_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory(['status_id' => Status::OPEN])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('status', Status::IN_PROGRESS)
//            ->call('save')
//            ->assertSuccessful();
//
//        $ticket = $ticket->refresh();
//
//        Livewire::test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Status:', 'In Progress', 'was', 'Open']);
    }

    /** @test */
    public function it_displays_on_hold_reason_changes_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory()->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('status', Status::ON_HOLD)
//            ->set('onHoldReason', OnHoldReason::CALLER_RESPONSE)
//            ->call('save')
//            ->assertSuccessful();
//
//        $ticket = $ticket->refresh();
//
//        Livewire::test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['On hold reason:', 'Caller Response', 'was', 'empty']);
    }

    /** @test */
    public function it_displays_priority_changes_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory(['priority' => Ticket::DEFAULT_PRIORITY])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('priority', 3)
//            ->set('priorityChangeReason', 'Production issue')
//            ->call('save')
//            ->assertSuccessful();
//
//        $ticket = $ticket->refresh();
//
//        Livewire::test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Priority:', '3', 'was', Ticket::DEFAULT_PRIORITY]);
    }

    /** @test */
    public function it_displays_group_changes_activity()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory(['group_id' => Group::SERVICE_DESK])->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('group', Group::LOCAL_6445_NEW_YORK)
//            ->call('save')
//            ->assertSuccessful();
//
//        $ticket = $ticket->refresh();
//
//        Livewire::test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Group:', 'LOCAL-6445-NEW-YORK', 'was', 'SERVICE-DESK']);
    }

    /** @test */
    public function it_displays_resolver_changes_activity()
    {
//        $resolver = User::factory(['name' => 'Average Joe'])->resolver(true)->create();
//        $ticket = Ticket::factory()->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('resolver', $resolver->id)
//            ->call('save')
//            ->assertSuccessful();
//
//        $ticket = $ticket->refresh();
//
//        Livewire::test(Activities::class, ['ticket' => $ticket])
//            ->assertSuccessful()
//            ->assertSeeInOrder(['Resolver:', 'Average Joe', 'was', 'empty']);
    }

    /** @test */
    public function it_displays_activities_in_descending_order()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory()->create();
//
//        $ticket->status_id = Status::IN_PROGRESS;
//        $ticket->save();
//
//        ActivityService::comment($ticket, 'Test Comment');
//
//        $ticket->status_id = Status::MONITORING;
//        $ticket->save();
//
//        $ticket->refresh();
//
//        Livewire::actingAs($resolver)
//            ->test(Activities::class, ['ticket' => $ticket])
//            ->assertSeeInOrder([
//                'Status:', 'Monitoring', 'was', 'In Progress',
//                'Test Comment',
//                'Status:', 'In Progress', 'was', 'Open',
//                'Created', 'Status:', 'Open',
//            ]);
    }

    /** @test */
    public function it_requires_priority_change_reason_if_priority_changes()
    {
//        $ticket = Ticket::factory()->create();
//        $resolver = User::factory()->resolver()->create();
//
//        Livewire::actingAs($resolver);
//
//        Livewire::test(TicketEditForm::class, ['ticket' => $ticket])
//            ->set('priority', 3)
//            ->call('save')
//            ->assertHasErrors(['priorityChangeReason' => 'required'])
//            ->set('priorityChangeReason', 'Production issue')
//            ->call('save')
//            ->assertSuccessful();
    }

    /** @test */
    public function sla_bar_shows_correct_minutes()
    {
//        $resolver = User::factory()->resolver()->create();
//        $ticket = Ticket::factory()->create();
//
//        $date = Carbon::now()->addMinutes(10);
//        Carbon::setTestNow($date);
//
//        Livewire::actingAs($resolver)
//            ->test(TicketEditForm::class, ['ticket' => $ticket])
//            ->assertSee($ticket->sla->minutesTillExpires() . ' minutes');
    }
}
