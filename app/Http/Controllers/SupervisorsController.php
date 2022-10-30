<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupervisorsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __invoke(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            abort(403, "You don't have permission to access this resource.");
        }

        $supervisors = User::with(['bloggers'])
            ->where('type', User::SUPERVISOR_TYPE)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('supervisors.list', compact('supervisors'));
    }
}
