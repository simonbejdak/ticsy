<?php

namespace App\Http\Controllers;

use App\Models\ConfigurationItem;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use Illuminate\Support\Facades\Auth;

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
