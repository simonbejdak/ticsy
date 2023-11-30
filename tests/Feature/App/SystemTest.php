<?php

namespace App;

use App\Http\Controllers\HomeController;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_email_1_hour_before_ticket_sla_expiration()
    {

    }
}
