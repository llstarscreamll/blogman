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
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('users')->assertRedirect('/login');
    }
}
