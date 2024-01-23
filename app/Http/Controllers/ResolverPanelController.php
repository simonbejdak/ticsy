<?php

namespace App\Http\Controllers;

use App\Helpers\Table;
use App\Models\Incident;

class ResolverPanelController extends Controller
{
    function incidents(){
        $table = new Table(Incident::class);

        return view('resolver-panel.incidents');
    }
}
