<?php

namespace App\Http\Controllers;

use App\Models\ConfigurationItem;

class ConfigurationItemsController extends Controller
{
    public function edit($id)
    {
        $configurationItem = ConfigurationItem::findOrFail($id);

        $this->authorize('edit', $configurationItem);

        return view('configuration-items.edit', [
            'configurationItem' => $configurationItem,
        ]);
    }
}
