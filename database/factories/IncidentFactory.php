<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Incident;
use App\Models\Resolver;
use App\Models\Ticket;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IncidentFactory extends TicketFactory
{
    protected $model = Incident::class;

    public function definition()
    {
        return array_merge(parent::definition(), [
            'category_id' => rand(1, count(Ticket::CATEGORIES)),
            'type_id' => Ticket::TYPES['incident'],
        ]);
    }
}
