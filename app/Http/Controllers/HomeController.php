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
        $user = $request->user();

        $statistics = [
            'user_posts_count' => $this->getPostsCountForUser($user),
            'user_types' => $user->isBlogger() ? [] : $this->getUserStatistics(),
        ];

        return view('home', compact('statistics'));
    }

    private function getPostsCountForUser($user): int
    {
        return Post::when(
            $user->isBlogger(),
            fn ($q) => $q->whereAuthorId($user->id)
        )->count();
    }

    private function getUserStatistics(): array
    {
        return DB::select(DB::raw(<<<MYSQL
        SELECT type name, count(id) count
        FROM users
        GROUP BY type;
        MYSQL));
    }
}
