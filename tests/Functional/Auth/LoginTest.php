<?php

namespace Tests\Functional\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function shouldRenderLoginFormSuccessfully()
    {
        $this->get('login')
            ->assertOk()
            ->assertSeeText('E-Mail Address')
            ->assertSee('name="email"')
            ->assertSeeText('Password')
            ->assertSee('name="password"')
            ->assertSeeText('Login');
    }

    /** @test */
    public function shouldCreateNewUserSuccessfullyWhenInputIsValid()
    {
        Carbon::setTestNow('2022-06-24 16:00:00');
        $user = factory(User::class)->create([
            'first_name' => 'Bruce',
            'last_name' => 'Banner',
            'email' => 'bruce_banner@avenger.com',
            'password' => Hash::make('S3cr3t/avengers'),
            'last_login' => now()->subMonths(5),
        ]);

        $this
            ->followingRedirects()
            ->post('login', [
                'email' => 'bruce_banner@avenger.com',
                'password' => 'S3cr3t/avengers',
            ])
            ->assertOk()
            ->assertSessionDoesntHaveErrors();

        $this->assertAuthenticatedAs($user);

        $this->assertDatabaseHas('users', [
            'email' => 'bruce_banner@avenger.com',
            'last_login' => now()->toDateTimeString(),
        ]);
    }
}
