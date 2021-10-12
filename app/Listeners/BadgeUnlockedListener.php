<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BadgeUnlockedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BadgeUnlocked  $event
     * @return void
     */
    public function handle(BadgeUnlocked $event)
    {
        $achievementMsg = collect(config("constant.". $event->type))->where('achievements', $event->badge_name)->first();

        $userAchievement = new Achievement();
        $userAchievement->user_id = $event->user->id;
        $userAchievement->type = $event->type;
        $userAchievement->achievements_key = $event->badge_name;
        $userAchievement->achievements = $achievementMsg['msg'];
        $userAchievement->save();
    }
}
