<?php

namespace Tests\Functional\Posts;

use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ListPostsTest extends TestCase
{
    /** @test */
    public function shouldRenderLastTwentyPostsFromStorage()
    {
        $actor = factory(User::class)->create();
        $expectedPosts = factory(Post::class, 30)->create()->reverse()->take(20);

        $this
            ->actingAs($actor)
            ->get('posts')
            ->assertOk()
            ->assertSeeText('Posts')
            ->assertSeeInOrder($expectedPosts->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->description->all())
            ->assertSeeInOrder($expectedPosts->map->author->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->crated_at->all());
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('posts')->assertRedirect('/login');
    }
}
