<?php


use App\Models\Request\Request;
use App\Models\OnHoldReason;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestOnHoldReasonTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_many_requests()
    {
        $onHoldReason = OnHoldReason::firstOrFail();
        Request::factory(2, ['on_hold_reason_id' => $onHoldReason])->statusOnHold()->create();
        $this->assertCount(2, $onHoldReason->requests);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $onHoldReason = OnHoldReason::findOrFail(OnHoldReason::WAITING_FOR_VENDOR);

        $this->assertEquals('Waiting For Vendor', $onHoldReason->name);
    }

    public function test_exception_thrown_if_status_on_hold_reason_assigned_but_status_different_than_on_hold()
    {
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason cannot be assigned to TicketTrait if Status is not on hold');

        Request::factory([
            'status_id' => Status::OPEN,
            'on_hold_reason_id' => OnHoldReason::CALLER_RESPONSE,
        ])->create();
    }

    public function test_exception_thrown_if_status_on_hold_selected_but_status_on_hold_reason_is_empty()
    {
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason must be assigned to TicketTrait if Status is on hold');

        Request::factory([
            'status_id' => Status::ON_HOLD,
        ])->create();
    }
}
