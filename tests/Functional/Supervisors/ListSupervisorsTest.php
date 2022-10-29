<?php

namespace Tests\Functional\Users;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ListSupervisorsTest extends TestCase
{
    /** @test */
    public function shouldRenderLastTwentySupervisors()
    {
        $actor = factory(User::class)->create();
        $expectedUsers = factory(User::class, 30)->create(['type' => User::SUPERVISOR_TYPE])->reverse()->take(20);
        factory(User::class, 7)->create(['type' => User::BLOGGER_TYPE]);
        factory(User::class, 7)->create(['type' => User::ADMIN_TYPE]);

        $this
            ->actingAs($actor)
            ->get('supervisors')
            ->assertOk()
            ->assertSeeText('Supervisors')
            ->assertSeeInOrder($expectedUsers->map->name->all())
            ->assertSeeInOrder($expectedUsers->map->email->all())
            ->assertSeeInOrder($expectedUsers->map->crated_at->all());
    }

    /** @test */
    public function shouldRenderUsersUnderSupervisors()
    {
        $actor = factory(User::class)->create();
        $supervisor = factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]);
        $bloggers = factory(User::class, 5)->create(['type' => User::BLOGGER_TYPE]);
        $supervisor->bloggers()->sync($bloggers);

        $this
            ->actingAs($actor)
            ->get('supervisors')
            ->assertOk()
            ->assertSeeInOrder($bloggers->map->name->all());
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('supervisors')->assertRedirect('/login');
    }
}
