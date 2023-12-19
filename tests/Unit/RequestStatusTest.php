<?php


use App\Models\Request\Request;
use App\Models\Request\RequestStatus;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestStatusTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_has_many_requests()
    {
        $status = Status::findOrFail(Status::OPEN);
        Request::factory(['description' => 'Request Description 1', 'status_id' => $status])->create();
        Request::factory(['description' => 'Request Description 2', 'status_id' => $status])->create();

        $i = 1;
        foreach ($status->requests as $request){
            $this->assertEquals('Request Description '.$i, $request->description);
            $i++;
        }
    }
}
