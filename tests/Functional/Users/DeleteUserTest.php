<?php

namespace Tests\Functional\Users;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    /** @test */
    public function shouldDeleteUserSuccessfully()
    {
        $userToDelete = factory(User::class)->create();

        $this
            ->followingRedirects()
            ->actingAs(factory(User::class)->create())
            ->delete("users/{$userToDelete->id}")
            ->assertOk()
            ->assertSee('User deleted successfully!');

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /** @test */
    public function shouldRenderForbiddenWhenUserIsBlogger()
    {
        $userToDelete = factory(User::class)->create();
        $blogger = factory(User::class)->create(['type' => User::BLOGGER_TYPE]);

        $this
            ->actingAs($blogger)
            ->delete("users/{$userToDelete->id}")
            ->assertForbidden();
    }

    /** @test */
    public function shouldRenderForbiddenWhenUserIsSupervisor()
    {
        $userToDelete = factory(User::class)->create();
        $blogger = factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]);

        $this
            ->actingAs($blogger)
            ->delete("users/{$userToDelete->id}")
            ->assertForbidden();
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $userToDelete = factory(User::class)->create();

        $this->delete("users/{$userToDelete->id}")->assertRedirect('/login');
    }
}
