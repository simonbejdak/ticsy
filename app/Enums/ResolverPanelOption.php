<?php

namespace App\Enums;

enum ResolverPanelOption: string
{
    case CONFIGURATION_ITEMS = 'Configuration Items';
    case INCIDENTS = 'Incidents';
    case REQUESTS = 'Requests';
    case TASKS = 'Tasks';
    case USERS = 'Users';

    function route(): string
    {
        return match($this){
            ResolverPanelOption::CONFIGURATION_ITEMS => 'resolver-panel.configuration-items',
            ResolverPanelOption::INCIDENTS => 'resolver-panel.incidents',
            ResolverPanelOption::REQUESTS => 'resolver-panel.requests',
            ResolverPanelOption::TASKS => 'resolver-panel.tasks',
            ResolverPanelOption::USERS => 'resolver-panel.users',
        };
    }
}
