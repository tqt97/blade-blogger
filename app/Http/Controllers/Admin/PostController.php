<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\BulkDeleteRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Traits\HandleBulkDelete;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    use HandleBulkDelete;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $posts = Post::query()
            ->with(['category:id,name', 'user:id,name'])
            ->status($status)
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.posts.create', [
            'options' => Category::query()->active()->options()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $path = ImageHelper::store($request->file('image'), 'posts');
                if ($path) {
                    $data['image_path'] = $path;
                    unset($data['image']);
                }
            }

            DB::transaction(function () use ($data) {
                Post::create($data);
            });

            return to_route('admin.posts.index')->with('success', __('post.messages.create_success'));
        } catch (\Throwable $th) {
            $this->logError('Failed to create post', $th);

            return to_route('admin.posts.index')->with('error', __('post.messages.create_fail'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        return view('admin.posts.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'options' => Category::query()->active()->options()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        try {
            $data = $request->validated();
            $oldImage = $post->image_path;
            $isNewImage = false;

            if ($request->hasFile('image')) {
                $path = ImageHelper::store($request->file('image'), 'posts');
                if ($path) {
                    $data['image_path'] = $path;
                    unset($data['image']);
                    $isNewImage = true;
                }
            }

            DB::transaction(function () use ($post, $data, $isNewImage, $oldImage) {
                $post->fill($data);
                if ($post->isDirty()) {
                    $post->save();
                }

                if ($isNewImage && $oldImage) {
                    DB::afterCommit(function () use ($oldImage) {
                        if (! ImageHelper::delete($oldImage)) {
                            Log::error('Failed to delete old image: '.$oldImage);
                        }
                    });
                }
            });

            return to_route('admin.posts.index')->with('success', __('post.messages.update_success'));
        } catch (\Throwable $th) {
            $this->logError('Failed to update post', $th, [
                'post_id' => $post->id,
            ]);

            return to_route('admin.posts.index')->with('error', __('post.messages.update_fail'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        try {
            $post->delete();

            return back()->with('success', __('post.messages.delete_success'));
        } catch (\Throwable $th) {
            $this->logError('Failed to delete post', $th, [
                'post_id' => $post->id,
            ]);

            return back()->with('error', __('post.messages.delete_fail'));
        }
    }

    /**
     * Remove multiple the specified resource from storage.
     */
    public function bulkDelete(BulkDeleteRequest $request): RedirectResponse
    {
        return $this->bulkDeleteGeneric($request, Post::class);
    }

    public function restore(Post $post): RedirectResponse
    {
        try {
            $post->restore();

            return back()->with('success', __('post.messages.restore_success'));
        } catch (\Throwable $th) {
            $this->logError('Failed to restore post', $th, [
                'post_id' => $post->id,
            ]);

            return back()->with('error', __('post.messages.restore_fail'));
        }
    }

    public function forceDelete(Post $post): RedirectResponse
    {
        try {
            DB::transaction(function () use ($post) {
                $post->forceDelete();
            });

            return back()->with('success', __('post.messages.force_delete_success'));
        } catch (\Throwable $th) {
            $this->logError('Failed to force delete post', $th, [
                'post_id' => $post->id,
            ]);

            return back()->with('error', __('post.messages.force_delete_fail'));
        }
    }

    protected function getOptions(): Collection
    {
        return Category::query()->active()->select('id', 'name')->get();
    }

    protected function logError(string $message, \Throwable $throwable, array $context = []): void
    {
        $baseContext = [
            'error' => $throwable->getMessage(),
            'user_id' => auth()->id(),
        ];

        Log::error($message, array_merge($baseContext, $context));
    }
}
