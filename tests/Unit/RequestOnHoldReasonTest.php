<?php


use App\Models\Category;
use App\Models\Item;
use App\Models\RequestOnHoldReason;
use App\Models\RequestStatus;
use App\Models\Status;
use App\Models\OnHoldReason;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestOnHoldReasonTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_many_requests()
    {
        $onHoldReason = RequestOnHoldReason::firstOrFail();
        Request::factory(2, ['on_hold_reason_id' => $onHoldReason])->statusOnHold()->create();
        $this->assertCount(2, $onHoldReason->requests);
    }

    public function test_it_uppercases_name_and_replaces_underscores_by_spaces()
    {
        $onHoldReason = RequestOnHoldReason::findOrFail(RequestOnHoldReason::WAITING_FOR_VENDOR);

        $this->assertEquals('Waiting For Vendor', $onHoldReason->name);
    }

    public function test_exception_thrown_if_status_on_hold_reason_assigned_but_status_different_than_on_hold()
    {
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason cannot be assigned to Request if Status is not on hold');

        Request::factory([
            'status_id' => RequestStatus::OPEN,
            'on_hold_reason_id' => RequestOnHoldReason::CALLER_RESPONSE,
        ])->create();
    }

    public function test_exception_thrown_if_status_on_hold_selected_but_status_on_hold_reason_is_empty()
    {
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('On hold reason must be assigned to Request if Status is on hold');

        Request::factory([
            'status_id' => RequestStatus::ON_HOLD,
        ])->create();
    }
}
