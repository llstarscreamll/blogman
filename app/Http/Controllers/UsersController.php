<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
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

        if ($user->isBlogger()) {
            abort(403, "You don't have permission to access this resource.");
        }

        $users = User::query()
            ->when($user->isSupervisor(), fn ($q) => $q->whereIn('id', $user->bloggers()->pluck('users.id')->push($user->id)))
            ->where('id', '!=', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('users.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            abort(403, "You don't have permission to access this resource.");
        }

        $title = 'Create user';
        $user = new User();

        return view('users.form', compact('title', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        if (! $request->user()->isAdmin()) {
            abort(403, "You don't have permission to access this resource.");
        }

        User::create(['password' => Hash::make($request->password)] + $request->validated());

        $request->session()->flash('success', 'User created successfully!');

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (! $request->user()->isAdmin()) {
            abort(403, "You don't have permission to access this resource.");
        }

        $title = 'Edit user';
        $user = User::findOrFail($id);

        return view('users.form', compact('title', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\EditUserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, $id)
    {
        if (! $request->user()->isAdmin()) {
            abort(403, "You don't have permission to access this resource.");
        }

        $user = User::findOrFail($id);
        $userData = $request->validated();

        if (!empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $request->session()->flash('success', 'User updated successfully!');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (! $request->user()->isAdmin()) {
            abort(403, "You don't have permission to access this resource.");
        }

        $user = User::findOrFail($id);

        $user->delete();
        session()->flash('success', 'User deleted successfully!');

        return redirect()->route('users.index');
    }
}
