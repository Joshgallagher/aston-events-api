<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Traits\FavourableTrait;
use App\Traits\FilterableTrait;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model implements HasMedia
{
    use FilterableTrait, FavourableTrait, HasMediaTrait, Searchable;

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

        static::addGlobalScope('favoritesCount', function ($builder) {
            $builder->withCount('favorites');
        });

        static::created(function ($event) {
            $event->update(['slug' => $event->name]);
        });

        static::deleting(function ($event) {
            $event->where('related_event_id', $event->id)->get()->each->update(['related_event_id' => null]);
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

    /**
     * Conversions that are applied to uploaded media.
     *
     * @param Media|null $media
     *
     * @return mixed
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('event-media')
            ->width(640)
            ->optimize()
            ->queued();
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => (string) $this->name,
            'location' => (string) $this->location,
            'favorites_count' => (int) $this->favorites_count,
            'category_name' => (string) $this->category->name,
        ];
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
