<?php


namespace Tests\Feature\Ticket;

use App\Exceptions\UnmatchedModelException;
use App\Models\Category;
use App\Models\Item;
use App\Models\TicketConfig;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->actingAs($user);
        $response = $this->post(route('tickets.store', [
            'type' => TicketConfig::TYPES['incident'],
            'category' => TicketConfig::CATEGORIES['network'],
            'item' => TicketConfig::ITEMS['failure'],
            'description' => $description,
            'priority' => TicketConfig::DEFAULT_PRIORITY,
        ]));

        $response->assertRedirectToRoute('tickets.edit', 1);
        $this->assertEquals($description, $user->tickets()->find(1)->description);
    }
}
