@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Supervisors</div>

                    <div class="card-body">
                        <table class="mt-4 table">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Assigned bloggers</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supervisors as $supervisor)
                                    <tr>
                                        <td>{{ $supervisor->first_name }} {{ $supervisor->last_name }}</td>
                                        <td>{{ $supervisor->email }}</td>
                                        <td>
                                            @foreach ($supervisor->bloggers as $blogger)
                                                {{ $blogger->name }}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>{{ $supervisor->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right">
                            {{ $supervisors->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
