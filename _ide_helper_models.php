<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedules newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedules newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedules onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedules query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedules withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedules withoutTrashed()
 */
	class Schedules extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchedulesView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchedulesView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchedulesView query()
 */
	class SchedulesView extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

