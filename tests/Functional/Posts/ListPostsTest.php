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
    public function shouldRenderLastTwentyPostsForAdmin()
    {
        $user = factory(User::class)->create(['type' => User::ADMIN_TYPE]);
        $expectedPosts = factory(Post::class, 30)->create()->reverse()->take(20);

        $this
            ->actingAs($user)
            ->get('posts')
            ->assertOk()
            ->assertSeeText('Posts')
            ->assertSeeInOrder($expectedPosts->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->description->all())
            ->assertSeeInOrder($expectedPosts->map->author->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->crated_at->all());
    }

    /** @test */
    public function shouldRenderLastTwentyPostsForBlogger()
    {
        $user = factory(User::class)->create(['type' => User::BLOGGER_TYPE]);
        $expectedPosts = factory(Post::class, 5)->create(['author_id' => $user])->reverse();

        $supervisorPost = factory(Post::class)->create([
            'name' => 'fake supervisor post',
            'author_id' => factory(User::class)->create(['type' => User::SUPERVISOR_TYPE])
        ]);
        $adminPost = factory(Post::class)->create([
            'name' => 'fake admin post',
            'author_id' => factory(User::class)->create(['type' => User::ADMIN_TYPE])
        ]);

        $this
            ->actingAs($user)
            ->get('posts')
            ->assertOk()
            ->assertSeeText('Posts')
            ->assertSeeInOrder($expectedPosts->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->description->all())
            ->assertSeeInOrder($expectedPosts->map->author->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->crated_at->all())
            ->assertDontSee($adminPost->name)
            ->assertDontSee($supervisorPost->name);
    }

    /** @test */
    public function shouldRenderPostsForSupervisor()
    {
        $user = factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]);
        $expectedPosts = factory(Post::class, 5)->create(['author_id' => $user])->reverse();

        $bloggerPost = factory(Post::class)->create([
            'name' => 'fake supervisor post',
            'author_id' => factory(User::class)->create(['type' => User::BLOGGER_TYPE])
        ]);
        $adminPost = factory(Post::class)->create([
            'name' => 'fake admin post',
            'author_id' => factory(User::class)->create(['type' => User::ADMIN_TYPE])
        ]);

        $this
            ->actingAs($user)
            ->get('posts')
            ->assertOk()
            ->assertSeeText('Posts')
            ->assertSeeInOrder($expectedPosts->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->description->all())
            ->assertSeeInOrder($expectedPosts->map->author->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->crated_at->all())
            ->assertDontSee($adminPost->name)
            ->assertDontSee($bloggerPost->name);
    }

    /** @test */
    public function shouldRenderSupervisorBloggerPosts()
    {
        $user = factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]);
        $expectedPosts = factory(Post::class, 5)->create(['author_id' => $user])->reverse();

        $bloggerPost = factory(Post::class)->create([
            'name' => 'fake supervisor post',
            'author_id' => $blogger = factory(User::class)->create(['type' => User::BLOGGER_TYPE])
        ]);
        $adminPost = factory(Post::class)->create([
            'name' => 'fake admin post',
            'author_id' => factory(User::class)->create(['type' => User::ADMIN_TYPE])
        ]);

        $user->bloggers()->attach($blogger);

        $this
            ->actingAs($user)
            ->get('posts')
            ->assertOk()
            ->assertSeeText('Posts')
            ->assertSeeInOrder($expectedPosts->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->description->all())
            ->assertSeeInOrder($expectedPosts->map->author->map->name->all())
            ->assertSeeInOrder($expectedPosts->map->crated_at->all())
            ->assertDontSee($adminPost->name)
            ->assertSee($bloggerPost->name);
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('posts')->assertRedirect('/login');
    }
}
