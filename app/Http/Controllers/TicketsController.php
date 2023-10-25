<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Incident;
use App\Models\Resolver;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TicketsController extends Controller
{
    const DEFAULT_PAGINATION = 10;

    public function index()
    {
        $user = Auth::user();
        $tickets = $user->tickets()
            ->with(['category', 'user', 'resolver'])
            ->orderByDesc('id')
            ->simplePaginate(self::DEFAULT_PAGINATION);

        return view('tickets.index', ['tickets' => $tickets]);
    }

    public function create($type = TicketConfig::DEFAULT_TYPE['name'])
    {
        $type = Type::where('name', '=', $type)->firstOrFail();
        $priorities = array_reverse(TicketConfig::PRIORITIES);

        return view('tickets.create', [
            'type' => $type,
            'categories' => Category::all(),
            'priorities' => $priorities,
            'default_priority' => TicketConfig::DEFAULT_PRIORITY,
        ]);
    }

    public function store(Request $request)
    {
        $min_desc = TicketConfig::MIN_DESCRIPTION_CHARS;
        $max_desc = TicketConfig::MAX_DESCRIPTION_CHARS;

        $validated = $request->validate([
            'type' => 'numeric|required|min:1|max:' . count(TicketConfig::TYPES),
            'category' => 'numeric|required|min:1|max:' . count(TicketConfig::CATEGORIES),
            'description' => 'string|required|min:' . $min_desc . '|max:' . $max_desc,
        ]);

        $ticket = new Ticket();
        $ticket->user_id = Auth::user()->id;
        $ticket->type_id = $validated['type'];
        $ticket->category_id = $validated['category'];
        $ticket->description = $validated['description'];
        $ticket->save();

        Session::flash('success', 'You have successfully created a ticket');
        return redirect()->route('tickets.edit', $ticket);
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);

        $this->authorize('edit', $ticket);

        return view('tickets.edit', [
            'ticket' => $ticket,
        ]);
    }
}
