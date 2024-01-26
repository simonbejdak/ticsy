<?php

namespace App\Http\Controllers;

class ResolverPanelController extends Controller
{
    function incidents(){
        return view('resolver-panel.incidents');
    }

    function requests(){
        return view('resolver-panel.requests');
    }

    function tasks(){
        return view('resolver-panel.tasks');
    }
}
