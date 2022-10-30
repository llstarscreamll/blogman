<?php

namespace Tests\Functional\Users;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /** @test */
    public function shouldRenderCreateUserFormWithCertainFields()
    {
        $this
            ->actingAs(factory(User::class)->create())
            ->get("users/create")
            ->assertOk()
            ->assertSeeText('Create user')
            ->assertSee('method="POST" action="'.route('users.store').'"')
            ->assertSee("name=\"_method\" value=\"POST\"")
            ->assertSeeText('First Name')
            ->assertSee('name="first_name"')
            ->assertSeeText('Last Name')
            ->assertSee('name="last_name"')
            ->assertSeeText('E-Mail Address')
            ->assertSee('name="email"')
            ->assertSeeText('Type')
            ->assertSee('name="type"')
            ->assertSee(User::BLOGGER_TYPE)
            ->assertSee(User::SUPERVISOR_TYPE)
            ->assertSee(User::ADMIN_TYPE)
            ->assertSeeText('Password')
            ->assertSee('name="password"')
            ->assertSeeText('Confirm Password')
            ->assertSee('name="password_confirmation"')
            ->assertSeeText('Save');
    }

    /** @test */
    public function shouldRenderForbiddenFormWhenUserIsBlogger()
    {
        $this
            ->actingAs(factory(User::class)->create(['type' => User::BLOGGER_TYPE]))
            ->get("users/create")
            ->assertForbidden();
    }

    /** @test */
    public function shouldRenderForbiddenFormWhenUserIsSupervisor()
    {
        $this
            ->actingAs(factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]))
            ->get("users/create")
            ->assertForbidden();
    }

    /** @test */
    public function shouldCreateUserSuccessfullyWhenUserIsAdmin()
    {
        Carbon::setTestNow('2022-06-24 16:00:00');
        $userToCreate = factory(User::class)->make();

        $this
            ->followingRedirects()
            ->from('users/create')
            ->actingAs(factory(User::class)->create())
            ->post('users', $userToCreate->toArray() + ['password' => 'S3cr3t#123', 'password_confirmation' => 'S3cr3t#123'])
            ->assertOk()
            ->assertSee('User created successfully!');

        $this->assertDatabaseHas('users', [
            'first_name' => $userToCreate->first_name,
            'last_name' => $userToCreate->last_name,
            'type' => $userToCreate->type,
            'email' => $userToCreate->email,
            'last_login' => null,
            'created_at' => now()->toDateTimeString()
        ]);

        $this->assertTrue(Hash::check('S3cr3t#123', User::whereEmail($userToCreate->email)->first()->password));
    }

    /** @test */
    public function shouldReturnForbiddenWhenUserIsBlogger()
    {
        $userToCreate = factory(User::class)->make();

        $this
            ->followingRedirects()
            ->from('users/create')
            ->actingAs(factory(User::class)->create(['type' => User::BLOGGER_TYPE]))
            ->post('users', $userToCreate->toArray() + ['password' => 'S3cr3t#123', 'password_confirmation' => 'S3cr3t#123'])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => $userToCreate->email]);
    }

    /** @test */
    public function shouldReturnForbiddenWhenUserIsSupervisor()
    {
        $userToCreate = factory(User::class)->make();

        $this
            ->followingRedirects()
            ->from('users/create')
            ->actingAs(factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]))
            ->post('users', $userToCreate->toArray() + ['password' => 'S3cr3t#123', 'password_confirmation' => 'S3cr3t#123'])
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => $userToCreate->email]);
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('users/create')->assertRedirect('/login');
    }
}
