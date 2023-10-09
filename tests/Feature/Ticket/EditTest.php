<?php


namespace Tests\Feature\Ticket;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;
    function test_it_redirect_guests_to_login_page()
    {
        $response = $this->get(route('tickets.edit', 1));

        $response->assertRedirectToRoute('login');
    }

    function test_it_errors_to_403_to_unauthorized_users()
    {
        $ticket = Ticket::factory()->create();
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('tickets.edit', $ticket));

        $response->assertForbidden();
    }

    function test_it_authorizes_caller_and_resolver_to_view(){
        $user = User::factory()->create();
        $resolver = User::factory()->create()->assignRole('resolver');
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $this->actingAs($user);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();

        $this->actingAs($resolver);
        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();
    }

    public function test_it_displays_ticket_data()
    {
        $type = Type::factory(['name' => 'incident'])->create();
        $category = Category::factory(['name' => 'network'])->create();
        $resolver = User::factory(['name' => 'John Doe'])->create()->assignRole('resolver');

        $user = User::factory()->create();
        $ticket = Ticket::factory([
            'type_id' => $type,
            'category_id' => $category,
            'resolver_id' => $resolver,
            'user_id' => $user,
        ])->create();


        $this->actingAs($user);

        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();
        $response->assertSee($type->name);
        $response->assertSee($category->name);
        $response->assertSee($resolver->name);
    }

    public function test_it_displays_comments()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory(['user_id' => $user])->create();

        $comment = Comment::factory([
            'body' => 'Comment Body',
            'ticket_id' => $ticket,
            'user_id' => $user,
        ])->create();

        $this->actingAs($user);

        $response = $this->get(route('tickets.edit', $ticket));
        $response->assertSuccessful();
        $response->assertSee('Comment Body');
    }
}
