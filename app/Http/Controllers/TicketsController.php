<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Incident;
use App\Models\Ticket;
use App\Models\Type;
use Illuminate\Http\Request;

class TicketsController extends Controller
{

    public function index()
    {
        $tickets = Ticket::with(['category', 'user', 'resolver'])
            ->latest()
            ->simplePaginate(Ticket::DEFAULT_PAGINATION);

        return view('tickets.index', ['tickets' => $tickets]);
    }

    public function show(Ticket $ticket)
    {
        $ticket = Ticket::findOrFail($ticket->id);

        return view('tickets.show', ['ticket' => $ticket]);
    }

    public function create($type = null)
    {
        $type = Type::where('name', '=', $type)->firstOrFail();

        $formType = ucfirst('create');
        $action = route('tickets.store');
        $priorities = array_reverse(Ticket::PRIORITIES);

        return view('tickets.create', [
            'type' => $type,
            'formType' => $formType,
            'categories' => Category::all(),
            'priorities' => $priorities,
            'default_priority' => Ticket::DEFAULT_PRIORITY,
            'action' => $action,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'numeric|required|min:1|max:' . count(Ticket::TYPES),
            'category' => 'numeric|required|min:1|max:' . count(Ticket::CATEGORIES),
            'description' => 'string|required|min:8|max:255',
            'priority' => 'numeric|required|min:1|max:' . count(Ticket::PRIORITIES),
        ]);

        $ticket = new Incident();
        $ticket->user_id = 1;
        $ticket->type_id = Ticket::TYPES['incident'];
        $ticket->category_id = $request['category'];
        $ticket->description = $request['description'];
        $ticket->save();

        return redirect()->route('tickets.show', $ticket);
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);

        $this->authorize('edit', $ticket);

        $formType = ucfirst('edit');
        $action = route('tickets.update', ['ticket' => $ticket]);

        return view('tickets.edit', [
            'ticket' => $ticket,
            'formType' => $formType,
            'categories' => Category::all(),
            'priorities' => Ticket::PRIORITIES,
            'action' => $action,
        ]);
    }

    public function update($id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);

        $this->authorize('destroy', $ticket);

        $request->validate([
            'priority' => 'numeric|required|min:1|max:' . count(Ticket::PRIORITIES),
        ]);

        $ticket->priority = $request['priority'];
        $ticket->save();

        return redirect()->route('tickets.show', $ticket);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        $this->authorize('destroy', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
