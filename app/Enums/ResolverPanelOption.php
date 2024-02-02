<?php

namespace App\Enums;

enum ResolverPanelOption: string
{
    case INCIDENTS = 'Incidents';
    case REQUESTS = 'Requests';
    case TASKS = 'Tasks';

    function route(): string
    {
        return match($this){
            ResolverPanelOption::INCIDENTS => 'resolver-panel.incidents',
            ResolverPanelOption::REQUESTS => 'resolver-panel.requests',
            ResolverPanelOption::TASKS => 'resolver-panel.tasks',
        };
    }
}
