<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    const RECENT_INCIDENTS_COUNT = 3;
    public function index()
    {
        $user = Auth::user();
        $data = [];
//
        if($user){
            $incidents = $user->incidents()
                ->with(['category', 'caller', 'resolver'])
                ->latest()
                ->take(self::RECENT_INCIDENTS_COUNT)
                ->get();
            if($incidents->count() > 0){
                $data['incidents'] = $incidents;
            }
        }

        return view('index', $data);
    }
}
