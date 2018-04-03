<?php

namespace App\Models;

use App\Filters\EventFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Boot the model.
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($event) {
            $event->update(['slug' => $event->name]);
        });
    }

    /**
     * An Event belongs to an Organiser (User).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * An Event belongs to a Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Apply all relevant Event filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Filters\EventFilters             $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, EventFilters $filters): Builder
    {
        return $filters->apply($query);
    }

    /**
     * Set the slug attribute.
     *
     * @param string $value
     */
    public function setSlugAttribute(string $value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }
}
