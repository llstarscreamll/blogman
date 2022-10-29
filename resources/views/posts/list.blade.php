@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Posts</div>

                    <div class="card-body">
                        <div class="text-right">
                            <a href="{{ route('posts.create') }}">Create post</a>
                        </div>

                        <table class="mt-4 table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Author</th>
                                    <th>Created at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>
                                        <td><a href="{{ route('posts.edit', ['post' => $post->id]) }}">{{ $post->name }}</a></td>
                                        <td>{{ $post->description }}</td>
                                        <td>{{ $post->author->name }}</td>
                                        <td>{{ $post->created_at }}</td>
                                        <td>
                                            <form action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                                                @method('DELETE') @csrf
                                                <button type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
