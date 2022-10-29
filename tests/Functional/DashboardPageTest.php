<?php

namespace Tests\Functional;

use App\User;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    /** @test */
    public function shouldRenderTheRootPage()
    {
        $this
            ->actingAs(factory(User::class)->create())
            ->get('/home')
            ->assertStatus(200)
            ->assertSeeText(config('app.name'))
            ->assertSee('Users');
    }
}
