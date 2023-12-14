<?php


use App\Models\Category;
use App\Models\Item;
use App\Models\Status;
use App\Models\OnHoldReason;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnHoldReasonTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_many_tickets()
    {
        $onHoldReason = OnHoldReason::firstOrFail();
        Ticket::factory([
            'on_hold_reason_id' => $onHoldReason,
            'description' => 'Ticket Description 1'
        ])->onHold()->create();

        Ticket::factory([
            'on_hold_reason_id' => $onHoldReason,
            'description' => 'Ticket Description 2'
        ])->onHold()->create();

        $this->assertEquals('Ticket Description 1', $onHoldReason->tickets()->findOrFail(1)->description);
        $this->assertEquals('Ticket Description 2', $onHoldReason->tickets()->findOrFail(2)->description);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $onHoldReason = OnHoldReason::findOrFail(OnHoldReason::WAITING_FOR_VENDOR);

        $this->assertEquals('Waiting For Vendor', $onHoldReason->name);
    }

    public function test_exception_thrown_if_status_on_hold_reason_assigned_but_status_different_than_on_hold()
    {
        $onHoldReason = OnHoldReason::findOrFail(OnHoldReason::CALLER_RESPONSE);

        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason cannot be assigned to Ticket if Status is not than on hold');

        Ticket::factory(['status_id' => Status::OPEN,
            'on_hold_reason_id' => $onHoldReason
        ])->create();
    }

    public function test_exception_thrown_if_status_on_hold_selected_but_status_on_hold_reason_is_empty()
    {
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason must be assigned to Ticket if Status is on hold');

        Ticket::factory()->onHold()->create();
    }
}
