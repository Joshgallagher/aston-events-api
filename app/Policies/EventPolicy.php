<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create events.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->confirmed;
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Event $event
     *
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $event->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the event.
     *
     * @param \App\Models\User  $user
     * @param \App\Models\Event $event
     *
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        return $event->user_id == $user->id;
    }
}
