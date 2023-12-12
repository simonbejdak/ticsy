<?php


namespace Tests\Feature\Ticket;

use App\Exceptions\UnmatchedModelException;
use App\Livewire\TicketCreateForm;
use App\Models\Category;
use App\Models\Item;
use App\Models\Ticket;
use App\Models\TicketConfig;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Str;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    function test_it_permits_authenticated_user_to_store_ticket(){
        $user = User::factory()->create();
        $description = 'Ticket Description';
        $category = Category::firstOrFail();
        $item = Item::firstOrFail();

        Livewire::actingAs($user)
            ->test(TicketCreateForm::class)
            ->set('category', $category->id)
            ->set('item', $item->id)
            ->set('description', $description)
            ->call('create');

        $this->assertEquals($description, $user->tickets()->find(1)->description);
    }
}
