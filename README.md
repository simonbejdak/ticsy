## Ticketing System

This is a fun project, which in it's current iteration offers reusable Livewire create, edit forms, and tables, which help with management of Entities (Entities are just extended Model) within the system. That involves:

- Conditional hiding and disabling of fields inside these form components on Controller level
- Easily configurable Tables (what query builder to work with, specify columns which to display)
- Service Level Agreement (SLA) assignment based on state of entity, the entity however needs to implement SLAble interface
- Activities, which are generated when state of Entity changes, these are automatically displayed within Edit Forms
- Comments, which can be added on Edit Forms, these are displayed together with activities
- Panel, which allows to save favorite panel options

The specific use case of the system is currently a Ticket Management (Incidents, Requests and Tasks). That involves creation, assignment of these entities to resolver groups, changing the status based on resolution state, and setting priority, which we use to calculate SLA duration. We are then able to search for these entities in respective tables located in panel option named Incidents, Requests and Tasks.

There are PHP packages like Filament, that do much more than that, but as mentioned above, this is a fun project, so I don't mind reinventing the wheel.

## How to use the system

After you run all the Laravel migrations just login with:
- E-mail: resolver@gmail.com
- Password: password

There are 3 total roles User, Resolver and Manager within the system.

User is able to only use Incident and Request create forms, and edit forms for Incidents and Requests created by themselves.

Resolver has all permissions as User able to view edit forms for all existing entities within the system. Also Resolver is able to view panel on the left side. There are links to Tables for Incidents, Requests and Tasks.

Manager has all permissions as Resolver and User, but in addition Manager is able to change Incident and Request priorities.

Role names are in match with email addresses, so user has email address "user@gmail.com". Password is always set as "password".

## Future plans

- Add support for custom table Filters
- Allow to save custom tables
- Implement customizable Dashboards, which will show arbitrary data based on provided logic by user
- Add new Entity "Changes", which will represent standard CAB like changes

