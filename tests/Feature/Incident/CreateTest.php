<?php


namespace Tests\Feature\Incident;

use App\Livewire\IncidentCreateForm;
use App\Mail\IncidentCreated;
use App\Mail\RequestCreated;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    function test_it_redirects_guests_to_login_page()
    {
        $response = $this->get(route('incidents.create'));

        $response->assertRedirectToRoute('login');
    }

    function test_it_loads_to_auth_users()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('incidents.create'));

        $response->assertSuccessful();
        $response->assertSee('Create Incident');
    }

    /**
     * @dataProvider invalidCategories
     */
    function test_it_fails_validation_with_invalid_category($value, $error){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(IncidentCreateForm::class)
            ->set('category', $value)
            ->call('create')
            ->assertHasErrors(['category' => $error]);
    }

    /**
     * @dataProvider invalidItems
     */
    function test_it_fails_validation_with_invalid_item($value, $error){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(IncidentCreateForm::class)
            ->set('item', $value)
            ->call('create')
            ->assertHasErrors(['item' => $error]);
    }

    /**
     * @dataProvider invalidDescription
     */
    function test_it_fails_validation_with_invalid_description($value, $error){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(IncidentCreateForm::class)
            ->set('description', $value)
            ->call('create')
            ->assertHasErrors(['description' => $error]);
    }

    function test_user_can_set_category(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(IncidentCreateForm::class)
            ->set('category', IncidentCategory::EMAIL)
            ->call('create')
            ->assertHasNoErrors(['category' => 'required']);
    }

    function test_user_can_set_item(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(IncidentCreateForm::class)
            ->set('item', IncidentItem::ISSUE)
            ->call('create')
            ->assertHasNoErrors(['item' => 'required']);
    }

    function test_user_can_set_description(){
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(IncidentCreateForm::class)
            ->set('description', 'TicketTrait Description')
            ->call('create')
            ->assertHasNoErrors(['description' => 'required']);
    }

    /** @test  */
    function mailable_incident_created_content_is_correct(){
        $incident = Incident::factory()->create();

        $mailable = new IncidentCreated($incident);

        $mailable->assertFrom(User::getSystemUser()->email);
        $mailable->assertHasSubject('Incident ' . $incident->id . ' has been opened for you');

        $mailable->assertSeeInHtml('Hello ' . $incident->caller->name);
        $mailable->assertSeeInHtml('An Incident has been opened on your behalf:');
        $mailable->assertSeeInHtml('Requested for: ' . $incident->caller->name);
        $mailable->assertSeeInHtml('Requested by: ' . $incident->caller->name);
        $mailable->assertSeeInHtml('Description: ' . $incident->description);
    }

    /** @test */
    function email_is_sent_when_incident_is_created_to_caller(){
        $caller = User::factory()->create();
        $category = IncidentCategory::findOrFail(IncidentCategory::EMAIL);
        $item = $category->randomItem();
        Mail::fake();

        Livewire::actingAs($caller)
            ->test(IncidentCreateForm::class)
            ->set('category', $category->id)
            ->set('item', $item->id)
            ->set('description', 'Test Description')
            ->call('create')
            ->assertSuccessful();

        Mail::assertSent(IncidentCreated::class, function (IncidentCreated $mail) use ($caller) {
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

