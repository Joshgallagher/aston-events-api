<?php

namespace App\Traits;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait FavourableTrait
{
    /**
     * Boot the trait.
     */
    protected static function bootFavourableTrait(): void
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * A Model can have many Favorites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * A Model can be favorited.
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

    /**
     * A Model can be unfavorited.
     *
     * @return mixed
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->get()->each->delete();
    }

    /**
     * Returns a boolean value indicating if the authenticated User
     * has favorited the give Model.
     *
     * @return bool
     */
    public function getFavoritedAttribute(): bool
    {
        $attributes = ['user_id' => auth()->id()];

        return $this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Returns the amount of Favourites a given Model has.
     *
     * @return int
     */
    public function getFavoritesCountAttribute(): int
    {
        return $this->favorites->count();
    }
}
