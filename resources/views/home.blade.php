@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Welcome {{ auth()->user()->first_name }}!</div>

                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">Email: {{ auth()->user()->email }}</li>
                            <li class="list-group-item">Last login: {{ auth()->user()->last_login }}</li>
                            <li class="list-group-item">Total posts: {{ $statistics['user_posts_count'] }}</li>
                            @foreach ($statistics['user_types'] as $type)
                                <li class="list-group-item">Total {{ strtolower(str_plural($type->name)) }}: {{ $type->count }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
