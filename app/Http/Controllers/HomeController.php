<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['category', 'user', 'resolver'])
            ->latest()
            ->take(3)
            ->get();

        return view('index', ['tickets' => $tickets]);
    }
}
