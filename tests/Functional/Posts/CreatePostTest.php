<?php

namespace Tests\Functional\Users;

use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    /** @test */
    public function shouldRenderCreatePostFormWithCertainFields()
    {
        $this
            ->actingAs(factory(User::class)->create())
            ->get("posts/create")
            ->assertOk()
            ->assertSeeText('Create post')
            ->assertSee('method="POST" action="'.route('posts.store').'"')
            ->assertSee("name=\"_method\" value=\"POST\"")
            ->assertSeeText('Name')
            ->assertSee('name="name"')
            ->assertSeeText('Description')
            ->assertSee('name="description"')
            ->assertSeeText('Save');
    }

    /** @test */
    public function shouldCreatePostSuccessfully()
    {
        Carbon::setTestNow('2022-06-24 16:00:00');
        $postToCreate = factory(Post::class)->make();

        $this
            ->followingRedirects()
            ->from('posts/create')
            ->actingAs($user = factory(User::class)->create())
            ->post('posts', $postToCreate->toArray())
            ->assertOk()
            ->assertSee('Post created successfully!');

        $this->assertDatabaseHas('posts', [
            'author_id' => $user->id,
            'name' => $postToCreate->name,
            'description' => $postToCreate->description,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    /** @test */
    public function shouldRedirectToLogInPageWhenUserIsUnauthenticated()
    {
        $this->get('posts')->assertRedirect('/login');
    }
}
