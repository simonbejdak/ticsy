<?php


namespace Tests\Feature\Incident;

use App\Exceptions\UnmatchedModelException;
use App\Livewire\IncidentCreateForm;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\TicketConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    function test_it_permits_authenticated_user_to_store_incident(){
        $user = User::factory()->create();
        $description = 'Ticket Description';
        $category = IncidentCategory::firstOrFail();
        $item = IncidentItem::firstOrFail();

        Livewire::actingAs($user)
            ->test(IncidentCreateForm::class)
            ->set('category', $category->id)
            ->set('item', $item->id)
            ->set('description', $description)
            ->call('create');

        $this->assertEquals($description, $user->incidents()->find(1)->description);
    }
}
