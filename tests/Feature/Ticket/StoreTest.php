<?php


namespace Tests\Feature\Ticket;

use App\Exceptions\UnmatchedModelException;
use App\Livewire\TicketCreateForm;
use App\Models\Category;
use App\Models\Item;
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
        $description = Str::random(TicketConfig::MIN_DESCRIPTION_CHARS + 1);
        $category = Category::findOrFail(TicketConfig::CATEGORIES['network']);
        $item = Item::find(TicketConfig::ITEMS['failure']);
        $item->categories()->attach($category);

        Livewire::actingAs($user)
            ->test(TicketCreateForm::class, ['type' => Type::findOrFail(TicketConfig::TYPES['incident'])])
            ->set('category', TicketConfig::CATEGORIES['network'])
            ->set('item', TicketConfig::ITEMS['failure'])
            ->set('description', $description)
            ->call('create');

        $this->assertEquals($description, $user->tickets()->find(1)->description);
    }
}
