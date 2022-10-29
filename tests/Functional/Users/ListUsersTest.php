<?php

namespace Tests\Functional\Users;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    /** @test */
    public function shouldRenderLastTwentyUsersFromStorage()
    {
        $actor = factory(User::class)->create();
        $expectedUsers = factory(User::class, 30)->create()->reverse()->take(20);

        $this
            ->actingAs($actor)
            ->get('users')
            ->assertOk()
            ->assertSeeText('Users')
            ->assertSeeInOrder($expectedUsers->map->first_name->all())
            ->assertSeeInOrder($expectedUsers->map->last_name->all())
            ->assertSeeInOrder($expectedUsers->map->email->all())
            ->assertSeeInOrder($expectedUsers->map->crated_at->all());
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('users')->assertRedirect('/login');
    }
}
