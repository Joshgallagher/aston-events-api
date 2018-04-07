<?php

namespace App\Models;

use App\Filters\IsFilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use IsFilterableTrait;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Boot the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::created(function ($event) {
            $event->update(['slug' => $event->name]);
        });
    }

    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * An Event belongs to an Organiser (User).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * An Event belongs to a Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * An Event has one related Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function relatedEvent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'related_event_id');
    }

    /**
     * An Event can be favorited.
     *
     * @return \App\Models\Favorite
     */
    public function favorite(): Favorite
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function isFavorited()
    {
        return $this->fsvorites()->where('user_id', auth()->id())->exists();
    }

    public function getFavoritesCountAttribute(): int
    {
        return $this->favorites->count();
    }

    /**
     * Set the slug attribute.
     *
     * @param string $value
     */
    public function setSlugAttribute(string $value): void
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }
}
