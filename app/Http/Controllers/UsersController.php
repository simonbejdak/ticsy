<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('edit', User::class);

        return view('users.edit', [
            'user' => $user,
        ]);
    }
}
