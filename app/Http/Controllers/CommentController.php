<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',

            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back()->with('success', __('comment.messages.create_success'));
    }

    public function update(Comment $comment, Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:3|max:1000',

        ]);

        if (trim($comment->content) !== trim($validated['content'])) { // ADDED WHITESPACE NORMALIZATION
            $comment->update([
                'content' => $validated['content'],
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => $comment->wasChanged(),
                'content' => $comment->content,
                'commentId' => $comment->id,
            ]);
        }

        return back()->with('success', __('comment.messages.update_success'));
    }

    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'commentId' => $comment->id]);
        }

        return back()->with('success', 'Comment deleted');
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $data = [
            'user_id' => auth()->id(),
            'post_id' => $comment->post_id,
            'parent_id' => $comment->id,
            'content' => $request->content,
        ];
        $reply = $comment->replies()->create($data);

        if ($request->ajax()) {
            $replyHtml = view('components.blogs.comment', ['comment' => $reply])->render();

            return response()->json([
                'success' => true,
                'replyHtml' => $replyHtml,
                'parentId' => $comment->id,
            ]);
        }

        return back()->with('success', __('comment.messages.reply_success'));

    }
}
