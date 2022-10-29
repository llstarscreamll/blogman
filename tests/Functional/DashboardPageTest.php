<?php

namespace Tests\Functional;

use App\Post;
use App\User;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    /** @test */
    public function shouldRenderAuthenticatedUserDetails()
    {
        $user = factory(User::class)->create(['type' => User::BLOGGER_TYPE]);
        factory(User::class, 5)->create(['type' => User::BLOGGER_TYPE]);
        factory(User::class, 3)->create(['type' => User::SUPERVISOR_TYPE]);
        $admins = factory(User::class, 7)->create(['type' => User::ADMIN_TYPE]);

        factory(Post::class, 30)->create(['author_id' => $user]);
        factory(Post::class, 20)->create(['author_id' => $admins->first()]);

        $this
            ->actingAs($user)
            ->get('/home')
            ->assertStatus(200)
            ->assertSee("Welcome $user->first_name!")
            ->assertSeeText(config('app.name'))
            ->assertSee('Users')
            ->assertSee('Posts')
            ->assertSee($user->name)
            ->assertSee($user->email)
            ->assertSee($user->last_login)
            ->assertSee("Total posts: 50")
            ->assertSee("Total bloggers: 6")
            ->assertSee("Total supervisors: 3")
            ->assertSee("Total admins: 7")
        ;
    }
}
