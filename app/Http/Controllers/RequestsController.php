<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Support\Facades\Auth;

class RequestsController extends Controller
{
    const DEFAULT_INDEX_PAGINATION = 10;

    public function index()
    {
        $user = Auth::user();
        $requests = $user->requests()
            ->with(['category', 'caller', 'resolver'])
            ->orderByDesc('id')
            ->simplePaginate(self::DEFAULT_INDEX_PAGINATION);

        return view('requests.index', ['requests' => $requests]);
    }

    public function create()
    {
        return view('requests.create');
    }

    public function edit(string $id)
    {
        $request = Request::findOrFail($id);

        $this->authorize('edit', $request);

        return view('requests.edit', [
            'request' => $request,
        ]);
    }
}
