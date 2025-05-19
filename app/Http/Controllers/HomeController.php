<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(HomeRequest $request): View
    {
        $requestValidated = $request->validated();

        $posts = Post::with(['category:id,name', 'user:id,name'])
            ->published()
            ->filter($requestValidated)
            ->sort($requestValidated)
            ->paginate($requestValidated['limit'] ?? 10)
            ->withQueryString();

        return view('client.home', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post): View
    {
        // $post->load(['comments']);
        $post
        // ->load(['comments', 'comments.user', 'comments.children.user'])
            ->loadCount('allComments');

        $comments = $post->comments()
            ->with('user', 'children.user') // eager load
            ->whereNull('parent_id') // chỉ lấy comment gốc
            ->latest()
            ->get();

        return view('client.show', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }
}
