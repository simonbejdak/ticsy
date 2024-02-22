<?php


namespace Tests\Feature\Request;

use App\Http\Controllers\RequestsController;
use App\Models\Request;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    function test_requests_index_redirects_guests_to_login_page()
    {
        $response = $this->get(route('requests.index'));

        $response->assertRedirectToRoute('login');
    }

    function test_requests_index_displays_requests_correctly()
    {
        $caller = User::factory(['name' => 'John Doe'])->create();
        $resolver = User::factory(['name' => 'Jeff Wing'])->resolver()->create();
        Request::factory([
            'description' => 'Request Description',
            'caller_id' => $caller,
            'resolver_id' => $resolver,
        ])->create();

        $this->actingAs($caller);
        $response = $this->get(route('requests.index'));

        $response->assertSuccessful();
        $response->assertSee('John Doe');
        $response->assertSee('Jeff Wing');
        $response->assertSee('Request Description');
    }

    function test_requests_index_pagination_displays_correct_number_of_requests()
    {
        $user = User::factory()->create();

        Request::factory([
            'description' => 'This request is supposed to be on the second pagination page',
            'caller_id' => $user,
        ])->create();

        Request::factory(RequestsController::DEFAULT_INDEX_PAGINATION, [
            'caller_id' => $user,
        ])->create();

        $this->actingAs($user);

        $response = $this->get(route('requests.index', ['page' => 1]));
        $response->assertSuccessful();
        $response->assertDontSee('This request is supposed to be on the second pagination page');

        $response = $this->get(route('requests.index', ['page' => 2]));
        $response->assertSuccessful();
        $response->assertSee('This request is supposed to be on the second pagination page');
    }
}
