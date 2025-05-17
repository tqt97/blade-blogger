<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Tag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FrontendLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $tags = Tag::query()
            ->withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->get();
        $categories = Category::query()
            ->active()
            ->withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->get();

        return view(
            'layouts.frontend-layout',
            [
                'tags' => $tags,
                'categories' => $categories,
            ]
        );
    }
}
