<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category_id',
        'is_featured',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->when($search, function ($q) use ($search) {
            $q->whereLike('title', '%'.$search.'%');
        });
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? false, fn ($query, $search) => $query->search($search))
            ->when($filters['category'] ?? false, fn ($query, $category) => $query->whereHas('category', fn ($query) => $query->where('slug', $category)))
            ->when($filters['author'] ?? false, fn ($query, $author) => $query->whereHas('user', fn ($query) => $query->where('username', $author)));
    }
}
