@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Users</div>

                    <div class="card-body">
                        <div class="text-right">
                            <a href="{{ route('users.create') }}">Add user</a>
                        </div>

                        <table class="mt-4 table">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Created at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td><a href="{{ route('users.edit', ['user' => $user->id]) }}">{{ $user->first_name }} {{ $user->last_name }}</a></td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->type }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>
                                            <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST">
                                                @method('DELETE') @csrf
                                                <button type="submit" class="btn">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
