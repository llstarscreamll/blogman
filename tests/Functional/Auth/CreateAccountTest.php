<?php

namespace Tests\Functional\Auth;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    /** @test */
    public function shouldRenderPageWithCertainFormElements()
    {
        $this->get('register')
            ->assertOk()
            ->assertSeeText('First Name')
            ->assertSee('name="first_name"')
            ->assertSeeText('Last Name')
            ->assertSee('name="last_name"')
            ->assertSeeText('E-Mail Address')
            ->assertSee('name="email"')
            ->assertSeeText('Password')
            ->assertSee('name="password"')
            ->assertSeeText('Confirm Password')
            ->assertSee('name="password_confirmation"')
            ->assertSeeText('Register');
    }

    /** @test */
    public function shouldCreateNewUserSuccessfullyWhenInputIsValid()
    {
        Carbon::setTestNow('2022-06-24 16:00:00');

        $this
            ->followingRedirects()
            ->post('register', [
                'first_name' => 'Bruce',
                'last_name' => 'Banner',
                'email' => 'bruce_banner@avenger.com',
                'password' => 'S3cr3t/avengers',
                'password_confirmation' => 'S3cr3t/avengers',
            ])
            ->assertOk()
            ->assertSessionDoesntHaveErrors()
            ->assertSee('Logout');

        $this->assertDatabaseHas('users', [
            'first_name' => 'Bruce',
            'last_name' => 'Banner',
            'email' => 'bruce_banner@avenger.com',
            'type' => User::BLOGGER_TYPE,
            'last_login' => now()->toDateTimeString(),
        ]);
    }
}
