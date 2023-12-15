<?php


namespace Tests\Feature\Request;

use App\Livewire\RequestCreateForm;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    function test_it_permits_authenticated_user_to_store_request(){
        $user = User::factory()->create();
        $description = 'Request Description';
        $category = RequestCategory::firstOrFail();
        $item = RequestItem::firstOrFail();

        Livewire::actingAs($user)
            ->test(RequestCreateForm::class)
            ->set('category', $category->id)
            ->set('item', $item->id)
            ->set('description', $description)
            ->call('create');

        $this->assertEquals($description, $user->requests()->first()->description);
    }
}
