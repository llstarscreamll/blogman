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
    public function shouldDeletePostSuccessfully()
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
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('posts')->assertRedirect('/login');
    }
}
