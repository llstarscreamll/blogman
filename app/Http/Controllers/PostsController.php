<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $posts = Post::query()
            ->when($user->isBlogger(), fn ($q) => $q->whereAuthorId($user->id))
            ->when($user->isSupervisor(), fn ($q) => $q->whereIn('author_id', $user->bloggers()->pluck('users.id')->push($user->id)))
            ->with(['author'])
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('posts.list', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create post';
        $post = new Post();

        return view('posts.form', compact('title', 'post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        Post::create(['author_id' => $request->user()->id] + $request->validated());

        $request->session()->flash('success', 'Post created successfully!');

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Post $post)
    {
        $title = 'Edit post';

        if (! $this->userCanAccessPost($request->user(), $post)) {
            abort(403, "You can't see this post");
        }

        return view('posts.form', compact('title', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CreatePostRequest  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePostRequest $request, Post $post)
    {
        if (! $this->userCanAccessPost($request->user(), $post)) {
            abort(403, "You can't see this post");
        }

        $post->update($request->validated());
        $request->session()->flash('success', 'Post updated successfully!');

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        if (! $this->userCanAccessPost($request->user(), $post)) {
            abort(403, "You can't see this post");
        }

        $post->delete();
        session()->flash('success', 'Post deleted successfully!');

        return redirect()->route('posts.index');
    }

    private function userCanAccessPost($user, $post): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isBlogger() && ! $post->author->is($user)) {
            return false;
        }

        if ($user->isSupervisor() && ! $user->bloggers()->pluck('users.id')->push($user->id)->contains($post->author_id)) {
            return false;
        }

        return true;
    }
}
