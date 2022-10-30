<?php

namespace Tests\Functional;

use App\Post;
use App\User;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    private User $admin;
    private User $blogger;
    private User $supervisor;

    protected function setUp()
    {
        parent::setUp();

        $this->blogger = factory(User::class)->create(['type' => User::BLOGGER_TYPE]);
        $this->supervisor = factory(User::class, 3)->create(['type' => User::SUPERVISOR_TYPE])->first();
        $this->admin = factory(User::class, 7)->create(['type' => User::ADMIN_TYPE])->first();
        $this->supervisor->bloggers()->sync(factory(User::class, 5)->create(['type' => User::BLOGGER_TYPE]));

        factory(Post::class, 2)->create(['author_id' => $this->supervisor->bloggers->first()]);
        factory(Post::class, 2)->create(['author_id' => $this->supervisor->bloggers->last()]);
        factory(Post::class, 2)->create(['author_id' => $this->supervisor]);
        factory(Post::class, 24)->create(['author_id' => $this->blogger]);
        factory(Post::class, 20)->create(['author_id' => $this->admin]);
    }

    /** @test */
    public function shouldRenderDashboardForBlogger()
    {
        $this
            ->actingAs($this->blogger)
            ->get('/home')
            ->assertStatus(200)
            ->assertSee("Welcome {$this->blogger->first_name}!")
            ->assertSeeText(config('app.name'))
            ->assertDontSee('Users')
            ->assertDontSee('Supervisors')
            ->assertSee('Posts')
            ->assertSee($this->blogger->name)
            ->assertSee($this->blogger->email)
            ->assertSee($this->blogger->last_login)
            ->assertSee("Total posts: 24")
            ->assertDontSee("Total bloggers: 6")
            ->assertDontSee("Total supervisors: 3")
            ->assertDontSee("Total admins: 7")
        ;
    }

    /** @test */
    public function shouldRenderDashboardForSupervisor()
    {
        $this
            ->actingAs($this->supervisor)
            ->get('/home')
            ->assertStatus(200)
            ->assertSee("Welcome {$this->supervisor->first_name}!")
            ->assertSeeText(config('app.name'))
            ->assertSee('Users')
            ->assertSee('Posts')
            ->assertSee($this->supervisor->name)
            ->assertSee($this->supervisor->email)
            ->assertSee($this->supervisor->last_login)
            ->assertSee("Total posts: 6")
            ->assertSee("Total bloggers: 5")
            ->assertDontSee("Total supervisors: 3")
            ->assertDontSee("Total admins: 7");
    }

    /** @test */
    public function shouldRenderDashboardForAdmin()
    {
        $this
            ->actingAs($this->admin)
            ->get('/home')
            ->assertStatus(200)
            ->assertSee("Welcome {$this->admin->first_name}!")
            ->assertSeeText(config('app.name'))
            ->assertSee('Users')
            ->assertSee('Posts')
            ->assertSee($this->admin->name)
            ->assertSee($this->admin->email)
            ->assertSee($this->admin->last_login)
            ->assertSee("Total posts: 50")
            ->assertSee("Total bloggers: 6")
            ->assertSee("Total supervisors: 3")
            ->assertSee("Total admins: 7")
        ;
    }
}
