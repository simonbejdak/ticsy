# Ticsy
Ticsy is a tool, which allows to manage ITIL processes and integrations like Incidents, Requests, Changes, CMDB, Users, Dashboards, Workflows, etc. Several of these are however still in development, so only some are fully covered. For now, Ticsy supports:

- Incident, Request and Task management
- Dynamic SLA calculation
- Arbitrary definitions of Create, Edit Forms and Tables on code level
- Intuitive user-friendly interface
- Personalizable Tables

[supported-databases]: https://laravel.com/docs/10.x/database
[php]: https://www.php.net/
[mysql]: https://www.mysql.com/
[composer]: https://getcomposer.org/
[node]: https://www.nodejs.org/
[git]: https://git-scm.com/

## Requirements
* [PHP][php] 8.2.12+
* [MySQL][mysql] 5.7+ (or any other DB system [supported][supported-databases] by Laravel)
* [Composer][composer] 2.7.1+
* [Node.js][node] 10.2.4+
* [Git][git]

## Installation
First of all, run the command:
```
git clone https://www.github.com/bejdakxd/ticsy
cd ticsy
cp .env.example .env
composer install
php artisan key:generate
npm install
npm run build
```
Next, create a new database in your DB system and modify the following entries in the .env file (located in the root of the folder) to match your database configuration:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel  <- name of the database, in my case I would replace 'laravel' by 'ticsy'
DB_USERNAME=root
DB_PASSWORD=
```
Finally, run these 2 commands:
```
php artisan migrate:fresh --seed
php artisan serve
```
Great. If your CLI displays a text like the one below, you should be able to access the application by using the link within the text.
> INFO  Server running on [http://127.0.0.1:8000]


## Usage
 There are many perspectives, which you can take on Ticsy. There is user, resolver and manager perspective. Let's start with the simplest one, the user perspective.

## User
User is able to register to the system by himself using Register option in the top menu. You can do the same, or you can log in using E-mail: user@gmail.com, and Password "password". 

Once you are logged in, you'll be able to create an Incident, or a Request. Let's look at both.

### Creating an Incident
Creating an Incident requires the user to pick a category, an item, and a description describing, what does the user need. Items are bound to categories, so they are rendered based on what category you pick in the first place. 

All 3 fields are mandatory, and not filling them results into validation highlight of an empty, mandatory field. Once the user successfully fill the form, and submit it, Ticsy creates the Incident, and redirects the user to its edit view. 

User is there able to monitor the state of the incident, its activity, and add comments below. 

### Creating a Request
Creating a Request is almost identical to creating an Incident, however the combination of picked Category and Item also creates a set of predefined tasks, which are displayed on the lower side of its edit view.

## Resolver
Resolvers have all user permissions, but also additional ones. That results into more abilities, and responsibilities within the application. Promoting a user to resolver requires a direct database manipulation (more on that in the Admin section). You can also log in as resolver using E-mail "resolver@gmail.com" and password "password". Let's explore more.

### Resolver Panel
Once a resolver logs in to Ticsy, a Resolver Panel shows up on the left side of Ticsy. There are several options there. Resolvers are able to save favorite ones by clicking on the star on the right of an option. For now, let's click on "Incidents".

### Tables
Resolvers are able to search text inside each column. Also, they are able to customize which column should be visible, and their order. Pagination on the right offers to jump to an arbitrary number of rendered objects. In the case of pagination Ticsy does not consider a non-integer, empty, or integer but outside the bounds of pagination as invalid, so in that case it simply returns back to index 1. Underscored texts are clickable links, which redirect to other pages.

To continue, select "Incidents" option in Resolver Panel, than click on a random underscored Incident number in the Table.

### Edit an Incident
We should now see an edit view of the Incident. Resolvers are able to change the status, group, and resolver of the Incident.

Status "On Hold" also requires to select an On hold reason.

Status "Resolved" makes other fields disabled. 72 hours after setting status as resolved renders the Incident as archived, making it impossible to change afterward.

Status "Cancelled" renders the Incident as archived, making it impossible to change afterward.

Selected Group also sets available list of resolvers, based on which resolvers belong to that group.

And changing most of these also requires to add a comment as an explanation why.

### Edit a Request
Editing a Request is similar to editing an Incident, however there are several connections between Request and Tasks generated by the request. 

Setting status of any Task as "Cancelled" or "Resolved" also sets the same status on the associated Request.

### Edit a Task
Editing a Task is the same as editing either an Incident, or Request.

### CMDB
CMDB is still in implementation.

### Dashboards
Dashboards are still in implementation.

### Workflows
Workflows are still in implementation.

## Manager
Managers have all the permissions as resolvers, but (for now) one more, which allows them to change priorities of Incidents, Requests and Tasks.

Changing a priority of either of them also results into changing a duration of an SLA assigned. Incident SLAs have duration shorter by half, than Request and Task SLAs.



