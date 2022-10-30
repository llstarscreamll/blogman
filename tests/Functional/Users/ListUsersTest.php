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
    public function shouldRenderSupervisorBloggersOnly()
    {
        $supervisor = factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]);
        $expectedUsers = factory(User::class, 2)->create()->reverse();
        $supervisor->bloggers()->sync($expectedUsers);

        $unexpectedUsers = factory(User::class, 2)
            ->create(['type' => User::SUPERVISOR_TYPE])
            ->concat(factory(User::class, 2)
            ->create(['type' => User::ADMIN_TYPE]))
            ->concat(factory(User::class, 2)
            ->create(['type' => User::BLOGGER_TYPE]));

        $response = $this
            ->actingAs($supervisor)
            ->get('users')
            ->assertOk()
            ->assertSeeText('Users')
            ->assertSeeInOrder($expectedUsers->map->name->all())
            ->assertSeeInOrder($expectedUsers->map->email->all())
            ->assertSeeInOrder($expectedUsers->map->crated_at->all());

        $unexpectedUsers->map->name->each(fn ($name) => $response->assertDontSee($name));
    }

    /** @test */
    public function shouldRenderForbiddenWhenUserIsBlogger()
    {
        $blogger = factory(User::class)->create(['type' => User::BLOGGER_TYPE]);

        $this
            ->actingAs($blogger)
            ->get('users')
            ->assertForbidden();
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('users')->assertRedirect('/login');
    }
}
