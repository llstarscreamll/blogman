<?php

namespace Tests\Functional;

use Tests\TestCase;

class RootPageTest extends TestCase
{
    /** @test */
    public function shouldRenderRootPage()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeText(config('app.name'));
    }
}
