<?php

namespace Tests\Functional\Users;

use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EditPostTest extends TestCase
{
    /** @test */
    public function shouldRenderEditPostFormWithCertainFieldsWhenUserIsAdmin()
    {
        $postToEdit = factory(Post::class)->create();

        $this
            ->actingAs(factory(User::class)->create(['type' => User::ADMIN_TYPE]))
            ->get("posts/{$postToEdit->id}/edit")
            ->assertOk()
            ->assertSeeText('Edit post')
            ->assertSee('method="POST" action="'.route('posts.update', ['post' => $postToEdit->id]).'"')
            ->assertSee("name=\"_method\" value=\"PUT\"")
            ->assertSeeText('Name')
            ->assertSee("name=\"name\" value=\"{$postToEdit->name}\"")
            ->assertSeeText('Description')
            ->assertSee("name=\"description\" value=\"{$postToEdit->description}\"")
            ->assertSeeText('Save');
    }

    /** @test */
    public function shouldReturnForbiddenWhenUserIsBloggerAndPostDoesNotBelongToUser()
    {
        $postToEdit = factory(Post::class)->create();

        $this
            ->actingAs(factory(User::class)->create(['type' => User::BLOGGER_TYPE]))
            ->get("posts/{$postToEdit->id}/edit")
            ->assertForbidden();
    }

    /** @test */
    public function shouldEditPostSuccessfullyWhenUserIsAdmin()
    {
        $originalPost = factory(Post::class)->create();
        $changes = factory(Post::class)->make();

        Carbon::setTestNow('2022-06-24 16:00:00');

        $this
            ->followingRedirects()
            ->actingAs(factory(User::class)->create(['type' => User::ADMIN_TYPE]))
            ->put("posts/{$originalPost->id}", $changes->toArray())
            ->assertOk()
            ->assertSee('Post updated successfully!');

        $this->assertDatabaseHas('posts', [
            'id' => $originalPost->id,
            'author_id' => $originalPost->author_id,
            'name' => $changes->name,
            'description' => $changes->description,
            'updated_at' => now()->toDateTimeString()
        ]);
    }

    /** @test */
    public function shouldReturnForbiddenWhenPostDoesNotBelongToBlogger()
    {
        $originalPost = factory(Post::class)->create();
        $changes = factory(Post::class)->make();

        Carbon::setTestNow('2022-06-24 16:00:00');

        $this
            ->followingRedirects()
            ->actingAs(factory(User::class)->create(['type' => User::BLOGGER_TYPE]))
            ->put("posts/{$originalPost->id}", $changes->toArray())
            ->assertForbidden();

        $this->assertDatabaseHas('posts', [
            'id' => $originalPost->id,
            'author_id' => $originalPost->author_id,
            'name' => $originalPost->name,
            'description' => $originalPost->description,
            'updated_at' => $originalPost->updated_at
        ]);
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('posts')->assertRedirect('/login');
    }
}
