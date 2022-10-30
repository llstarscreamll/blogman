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

        if ($request->user()->isBlogger() && ! $post->author->is($request->user())) {
            abort(403, "You are not the post's author");
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
        if ($request->user()->isBlogger() && ! $post->author->is($request->user())) {
            abort(403, "You are not the post's author");
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
        if ($request->user()->isBlogger() && ! $post->author->is($request->user())) {
            abort(403, "You are not the post's author");
        }

        $post->delete();
        session()->flash('success', 'Post deleted successfully!');

        return redirect()->route('posts.index');
    }
}
