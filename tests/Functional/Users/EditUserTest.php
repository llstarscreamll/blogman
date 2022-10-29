<?php

namespace Tests\Functional\Users;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    /** @test */
    public function shouldRenderEditUserFormWithCertainFields()
    {
        $userToEdit = factory(User::class)->create();

        $this
            ->actingAs(factory(User::class)->create())
            ->get("users/{$userToEdit->id}/edit")
            ->assertOk()
            ->assertSeeText('Edit user')
            ->assertSee('method="POST" action="'.route('users.update', ['user' => $userToEdit->id]).'"')
            ->assertSee("name=\"_method\" value=\"PUT\"")
            ->assertSeeText('First Name')
            ->assertSee("name=\"first_name\" value=\"{$userToEdit->first_name}\"")
            ->assertSeeText('Last Name')
            ->assertSee("name=\"last_name\" value=\"{$userToEdit->last_name}\"")
            ->assertSeeText('E-Mail Address')
            ->assertSee("name=\"email\" value=\"{$userToEdit->email}\"")
            ->assertSeeText('Type')
            ->assertSee("name=\"type\" value=\"{$userToEdit->type}\"")
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
    public function shouldEditUserSuccessfully()
    {
        $userToEdit = factory(User::class)->create();
        $payload = factory(User::class)->make(['type' => User::SUPERVISOR_TYPE]);

        Carbon::setTestNow('2022-06-24 16:00:00');

        $this
            ->followingRedirects()
            ->actingAs(factory(User::class)->create())
            ->put("users/{$userToEdit->id}", $payload->toArray() + ['password' => 'S3cr3t#123', 'password_confirmation' => 'S3cr3t#123'])
            ->assertOk()
            ->assertSee('User updated successfully!');

        $this->assertDatabaseHas('users', [
            'id' => $userToEdit->id,
            'first_name' => $payload->first_name,
            'last_name' => $payload->last_name,
            'type' => $payload->type,
            'email' => $payload->email,
            'updated_at' => now()->toDateTimeString()
        ]);

        $this->assertTrue(Hash::check('S3cr3t#123', $userToEdit->refresh()->password));
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('users')->assertRedirect('/login');
    }
}
