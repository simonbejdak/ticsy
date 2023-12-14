<?php

namespace App\Http\Controllers;

use App\Models\Request;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
