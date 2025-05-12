<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tags = Tag::withCount('posts')->latest()->paginate();

        return view('admin.tags.index', [
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        try {
            Tag::query()->create($request->validated());

            return to_route('admin.tags.index')->with('success', __('tag.messages.create_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return to_route('admin.tags.index')->with('error', __('tag.messages.create_fail'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag): View
    {
        return view('admin.tags.show', [
            'tag' => $tag,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', [
            'tag' => $tag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        try {
            $tag->update($request->validated());

            return to_route('admin.tags.index')->with('success', __('tag.messages.update_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return to_route('admin.tags.index')->with('error', __('tag.messages.update_fail'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        try {
            $tag->delete();

            return to_route('admin.tags.index')->with('success', __('tag.messages.delete_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return to_route('admin.tags.index')->with('error', __('tag.messages.delete_fail'));
        }
    }

    public function bulkDelete(): RedirectResponse
    {
        try {
            Tag::query()->whereIn('id', request('ids'))->delete();

            return to_route('admin.tags.index')->with('success', __('tag.messages.bulk_delete_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return to_route('admin.tags.index')->with('error', __('tag.messages.bulk_delete_fail'));
        }
    }
}
