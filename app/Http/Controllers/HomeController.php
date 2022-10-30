<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
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
            'user_types' => $this->getUserStatisticsForUser($user),
        ];

        return view('home', compact('statistics'));
    }

    private function getPostsCountForUser(User $user): int
    {
        return Post::when(
            $user->isBlogger(),
            fn ($q) => $q->whereAuthorId($user->id)
        )
        ->when(
            $user->isSupervisor(),
            fn ($q) => $q->whereIn('author_id', $user->bloggers()
                ->where('type', User::BLOGGER_TYPE)
                ->pluck('users.id')
                ->push($user->id))
        )->count();
    }

    private function getUserStatisticsForUser(User $user): array
    {
        if ($user->isBlogger()) {
            return [];
        }

        return $user->isSupervisor()
            ? $this->getUserStaticsForSupervisor($user)
            : $this->getUserStaticsForAdmin();
    }

    private function getUserStaticsForAdmin(): array
    {
        return DB::select(DB::raw(<<<MYSQL
        SELECT type name, count(id) count
        FROM users
        WHERE type in (?,?,?)
        GROUP BY type;
        MYSQL), [User::BLOGGER_TYPE, User::SUPERVISOR_TYPE, User::ADMIN_TYPE]);
    }

    private function getUserStaticsForSupervisor(User $supervisor): array
    {
        return [
            (object) [
                'name' => User::BLOGGER_TYPE,
                'count' => $supervisor->bloggers()
                ->where('type', User::BLOGGER_TYPE)
                ->pluck('users.id')
                ->count()
            ]
        ];
    }
}
