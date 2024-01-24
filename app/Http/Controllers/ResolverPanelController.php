<?php

namespace App\Http\Controllers;

use App\Helpers\Table;
use App\Models\Incident;
use App\Models\Request;
use App\Models\Task;

class ResolverPanelController extends Controller
{
    function incidents(){
        $table = Table::make(Incident::query()->orderByDesc('id'))
            ->column('Number', 'id', ['incidents.edit', 'id'])
            ->column('Caller', 'caller.name')
            ->column('Resolver', 'resolver.name')
            ->column('Status', 'status.value')
            ->column('Priority', 'priority.value');

        return view('resolver-panel.incidents', ['table' => $table]);
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
