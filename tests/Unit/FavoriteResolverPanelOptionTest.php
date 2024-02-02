<?php

use App\Models\FavoriteResolverPanelOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteResolverPanelOptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_belongs_to_user(){
        $user = User::factory()->create();
        $favoriteResolverPanelOption = FavoriteResolverPanelOption::factory([
            'user_id' => $user
        ])->create();

        $this->assertTrue($user->is($favoriteResolverPanelOption->user));
    }
}
