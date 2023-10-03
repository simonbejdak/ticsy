<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    const RECENT_TICKETS_COUNT = 3;
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if($user){
            $tickets = $user->tickets()
                ->with(['category', 'user', 'resolver'])
                ->latest()
                ->take(self::RECENT_TICKETS_COUNT)
                ->get();
            if($tickets->count() > 0){
                $data['tickets'] = $tickets;
            }
        }

        return view('index', $data);
    }
}
