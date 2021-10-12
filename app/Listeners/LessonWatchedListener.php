<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LessonUser;
use DB;

class LessonWatchedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // dump("listener call"); exit();
    }

    /**
     * Handle the event.
     *
     * @param  LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $lessonWatched = new LessonUser();
        $lessonWatched->user_id = $event->user->id;
        $lessonWatched->lesson_id = $event->lesson->id;
        $lessonWatched->watched = 1;
        $lessonWatched->save();

        $lessonCounts = LessonUser::where('user_id', $lessonWatched->user_id)->groupBy('lesson_id')->select('lesson_id',DB::raw('count(*) as total'))->get()->toArray();
        $lessonMsg = collect(config("constant.lessons"))->where('watched_lesson', count($lessonCounts))->first();

        if ($lessonMsg) {
            AchievementUnlocked::dispatch(count($lessonCounts), 'lessons', $lessonWatched->user);
        }
    }
}
