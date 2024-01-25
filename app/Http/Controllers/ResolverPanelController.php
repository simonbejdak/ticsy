<?php

namespace App\Http\Controllers;

use App\Helpers\Table\Table;
use App\Models\Request;
use App\Models\Task;

class ResolverPanelController extends Controller
{
    function incidents(){
        return view('resolver-panel.incidents');
    }

    function requests(){
        $table = Table::make(Request::query()->orderByDesc('id'))
            ->column('Number', 'id', ['requests.edit', 'id'])
            ->column('Caller', 'caller.name')
            ->column('Resolver', 'resolver.name')
            ->column('Status', 'status.value')
            ->column('Priority', 'priority.value');

        return view('resolver-panel.requests', ['table' => $table]);
    }

    function tasks(){
        $table = Table::make(Task::query()->orderByDesc('id'))
            ->column('Number', 'id', ['tasks.edit', 'id'])
            ->column('Caller', 'caller.name')
            ->column('Resolver', 'resolver.name')
            ->column('Status', 'status.value')
            ->column('Priority', 'priority.value');

        return view('resolver-panel.tasks', ['table' => $table]);
    }
}
