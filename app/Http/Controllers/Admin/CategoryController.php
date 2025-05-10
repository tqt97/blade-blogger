<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $categories = Category::query()
            ->search($search)
            ->select('id', 'name', 'slug', 'parent_id', 'position', 'is_active')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::query()
            ->active()
            ->select('id', 'name')
            ->get();

        return view('admin.categories.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            Category::query()->create($request->validated());

            return to_route('admin.categories.index')->with('success', __('category.messages.create_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return back()
                ->withInput()
                ->with('error', __('category.messages.create_fail'));
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        return view('admin.categories.show', [
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $categoryOptions = Category::query()->active()
            ->select('id', 'name')
            ->get();

        return view('admin.categories.edit', [
            'category' => $category,
            'categoryOptions' => $categoryOptions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $category->update($request->validated());

            return to_route('admin.categories.index')->with('success', __('category.messages.update_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return back()->with('error', __('category.messages.update_fail'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            $category->delete();

            return to_route('admin.categories.index')->with('success', __('category.messages.delete_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return back()->with('error', __('category.messages.delete_fail'));
        }
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        try {
            $ids = explode(',', $request->input('ids'));
            if (empty($ids)) {
                return back()->with('warning', __('category.messages.bulk_delete_empty'));
            }
            Category::query()->whereIn('id', $ids)->delete();

            return to_route('admin.categories.index')->with('success', __('category.messages.bulk_delete_success'));
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return back()->with('error', __('category.messages.bulk_delete_fail'));
        }
    }
}
