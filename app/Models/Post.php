<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    use Prunable;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'image_path',
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

    protected static function boot(): void
    {
        parent::boot();
        static::deleting(function (Post $post) {
            if ($post->isForceDeleting()) {
                $oldImage = $post->image_path;

                if ($oldImage && ! ImageHelper::delete($oldImage)) {
                    throw new \RuntimeException("Delete image fail: {$oldImage}");
                }
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Delete the image associated with this post, if any, before pruning.
     */
    public function prepareForPrune()
    {
        if ($this->image_path) {
            ImageHelper::delete($this->image_path);
        }
    }

    /**
     * Get the prunable model query.
     *
     * Determine which models should be pruned.
     * This is typically a query that filters the soft deleted models.
     */
    public function prunable(): Builder
    {
        return static::query()->onlyTrashed()->where('deleted_at', '<', now()->subMonth());
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the URL of the image associated with this post, or a default
     * placeholder image if no image is associated.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : asset('assets/images/no-image.png');
    }

    /**
     * Scope the query to include soft deleted models or only the trashed ones.
     */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'trashed' => $query->onlyTrashed(),
            'all' => $query->withTrashed(),
            default => $query,
        };
    }

    /**
     * Scope the query to include only featured posts.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope the query to include only published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to enable search functionality.
     *
     * @param  string|null  $search  The search term to filter the query results by title.
     */
    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->when($search, function ($q) use ($search) {
            $q->whereLike('title', '%'.$search.'%');
        });
    }

    /**
     * Scope a query to apply filters for search, category, and author.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['search'] ?? false, fn ($query, $search) => $query->search($search))
            ->when($filters['category'] ?? false, fn ($query, $category) => $query->whereHas('category', fn ($query) => $query->where('slug', $category)))
            ->when($filters['author'] ?? false, fn ($query, $author) => $query->whereHas('user', fn ($query) => $query->where('username', $author)));
    }
}
