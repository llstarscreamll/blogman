<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $statistics = [
            'user_posts_count' => Post::count(),
            'user_types' => DB::select(DB::raw(<<<MYSQL
                SELECT type name, count(id) count
                FROM users
                GROUP BY type;
                MYSQL))
        ];

        return view('home', compact('statistics'));
    }
}
