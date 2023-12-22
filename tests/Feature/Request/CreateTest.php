<?php


namespace Tests\Feature\Request;

use App\Livewire\RequestCreateForm;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Request\RequestCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_redirects_guests_to_login_page()
    {
        $response = $this->get(route('requests.create'));

        $response->assertRedirectToRoute('login');
    }

    /** @test */
    function it_loads_to_logged_in_users()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('requests.create'));

        $response->assertSuccessful();
        $response->assertSee('Create Request');
    }

    /**
     * @test
     * @dataProvider invalidCategories
     */
    function it_fails_validation_with_invalid_category($value, $error){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(RequestCreateForm::class)
            ->set('category', $value)
            ->call('create')
            ->assertHasErrors(['category' => $error]);
    }

    /**
     * @test
     * @dataProvider invalidItems
     */
    function it_fails_validation_with_invalid_item($value, $error){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(RequestCreateForm::class)
            ->set('item', $value)
            ->call('create')
            ->assertHasErrors(['item' => $error]);
    }

    /**
     * @test
     * @dataProvider invalidDescription
     */
    function it_fails_validation_with_invalid_description($value, $error){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(RequestCreateForm::class)
            ->set('description', $value)
            ->call('create')
            ->assertHasErrors(['description' => $error]);
    }

    function test_user_can_set_category(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(RequestCreateForm::class)
            ->set('category', RequestCategory::SERVER)
            ->call('create')
            ->assertHasNoErrors(['category' => 'required']);
    }

    function test_user_can_set_item(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(RequestCreateForm::class)
            ->set('item', IncidentItem::ISSUE)
            ->call('create')
            ->assertHasNoErrors(['item' => 'required']);
    }

    function test_user_can_set_description(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(RequestCreateForm::class)
            ->set('description', 'Request Description')
            ->call('create')
            ->assertHasNoErrors(['description' => 'required']);
    }

    /** @test */
    function correct_categories_are_visible(){
        $user = User::factory()->create();
        $categories = RequestCategory::all();

        Livewire::actingAs($user);

        foreach ($categories as $category){
            Livewire::test(RequestCreateForm::class)
                ->assertSee($category->name);
        }
    }

    static function invalidCategories(){
        return [
            ['ASAP', 'numeric'],
            ['', 'required'],
        ];
    }

    static function invalidItems(){
        return [
            ['', 'required'],
            ['ASAP', 'numeric'],
        ];
    }

    static function invalidDescription(){
        return [
            ['', 'required'],
        ];
    }
}

