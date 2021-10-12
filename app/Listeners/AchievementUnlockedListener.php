<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Achievement;

class AchievementUnlockedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  AchievementUnlocked  $event
     * @return void
     */
    public function handle(AchievementUnlocked $event)
    {
        $type = ($event->type == 'comments') ? 'written_comment' : 'watched_lesson';
        $achievementMsg = collect(config("constant." . $event->type))->where($type, $event->achievement_name)->first();
        $userAchievement = new Achievement();
        $userAchievement->user_id = $event->user->id;
        $userAchievement->type = $event->type;
        $userAchievement->achievements_key = $event->achievement_name;
        $userAchievement->achievements = $achievementMsg['msg'];
        $userAchievement->save();

        // get count of total achievements
        $BadgeCounts = Achievement::where('user_id', $userAchievement->user_id)->count();
        $badgeMsg = collect(config("constant.badges"))->where('achievements', $BadgeCounts)->first();
        if ($badgeMsg) {
            BadgeUnlocked::dispatch($BadgeCounts, 'badges', $userAchievement->user);
        }

        return response()->json(['data'=> $userAchievement]);
    }
}
