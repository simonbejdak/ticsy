<?php


namespace Tests\Feature\Request;

use App\Livewire\RequestCreateForm;
use App\Mail\RequestCreated;
use App\Models\Incident\IncidentItem;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
        $response->assertSee('Create a Request');
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

    /** @test  */
    function mailable_request_created_content_is_correct(){
        $request = Request::factory()->create();

        $mailable = new RequestCreated($request);

        $mailable->assertFrom(User::getSystemUser()->email);
        $mailable->assertHasSubject('Request ' . $request->id . ' has been opened for you');

        $mailable->assertSeeInHtml('Hello ' . $request->caller->name);
        $mailable->assertSeeInHtml('A Request has been opened on your behalf:');
        $mailable->assertSeeInHtml('Requested for: ' . $request->caller->name);
        $mailable->assertSeeInHtml('Requested by: ' . $request->caller->name);
        $mailable->assertSeeInHtml('Description: ' . $request->description);
    }

    /** @test */
    function email_is_sent_when_request_is_created_to_caller(){
        $caller = User::factory()->create();
        $category = RequestCategory::findOrFail(RequestCategory::SERVER);
        $item = $category->randomItem();
        Mail::fake();

        Livewire::actingAs($caller)
            ->test(RequestCreateForm::class)
            ->set('category', $category->id)
            ->set('item', $item->id)
            ->set('description', 'Test Description')
            ->call('create')
            ->assertSuccessful();

        Mail::assertSent(RequestCreated::class, function (RequestCreated $mail) use ($caller) {
            return $mail->hasTo($caller->email);
        });
    }

    static function invalidCategories(){
        return [
            ['ASAP', 'in'],
            ['', 'required'],
        ];
    }

    static function invalidItems(){
        return [
            ['', 'required'],
            ['ASAP', 'in'],
        ];
    }

    static function invalidDescription(){
        return [
            ['', 'required'],
        ];
    }
}

