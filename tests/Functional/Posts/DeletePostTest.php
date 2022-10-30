<?php

namespace Tests\Functional\Users;

use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    /** @test */
    public function shouldDeletePostSuccessfullyWhenUserIsAdmin()
    {
        $postToDelete = factory(Post::class)->create();

        $this
            ->followingRedirects()
            ->actingAs(factory(User::class)->create())
            ->delete("posts/{$postToDelete->id}")
            ->assertOk()
            ->assertSee('Post deleted successfully!');

        $this->assertDatabaseMissing('posts', ['id' => $postToDelete->id]);
    }

    /** @test */
    public function shouldDeletePostSuccessfullyWhenPostBelongsToSupervisorBlogger()
    {
        $postToDelete = factory(Post::class)->create();
        $supervisor = factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]);
        $supervisor->bloggers()->attach($postToDelete->author);

        $this
            ->followingRedirects()
            ->actingAs($supervisor)
            ->delete("posts/{$postToDelete->id}")
            ->assertOk()
            ->assertSee('Post deleted successfully!');

        $this->assertDatabaseMissing('posts', ['id' => $postToDelete->id]);
    }

    /** @test */
    public function shouldReturnForbiddenWhenPostDoesNotBelongToBlogger()
    {
        $postToDelete = factory(Post::class)->create();

        $this
            ->followingRedirects()
            ->actingAs(factory(User::class)->create(['type' => User::BLOGGER_TYPE]))
            ->delete("posts/{$postToDelete->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $postToDelete->id]);
    }

    /** @test */
    public function shouldReturnForbiddenWhenPostDoesNotBelongToSupervisorBlogger()
    {
        $postToDelete = factory(Post::class)->create();

        $this
            ->followingRedirects()
            ->actingAs(factory(User::class)->create(['type' => User::SUPERVISOR_TYPE]))
            ->delete("posts/{$postToDelete->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $postToDelete->id]);
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('posts')->assertRedirect('/login');
    }
}
