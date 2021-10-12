<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $userId = $user->id;
        $achievements = Achievement::where('user_id',$userId)->where('type','<>', 'badges')->pluck('achievements')->toArray();
        $next = $this->getBadge($userId, 'next');
        return response()->json([
            'unlocked_achievements' => $achievements,
            'next_available_achievements' => [$this->getLesson($userId), $this->getComment($userId)],
            'current_badge' => $this->getBadge($userId),
            'next_badge' => $next,
            'remaing_to_unlock_next_badge' => $this->getRemainingAchievements($userId, $next)
        ]);
    }

    /**
     * get lessons of user function
     *
     * @param [type] $userId
     * @return void
     */
    public function getLesson($userId)
    {
        $lesson = Achievement::where('user_id', $userId)->where('type', 'lessons')->latest()->first();
        if(!$lesson){
            return;
        }
        return $this->getNextRecord('lessons', 'watched_lesson', $lesson->achievements_key);
    }

    /**
     * get comments of user function
     *
     * @param [type] $userId
     * @return void
     */

    public function getComment($userId)
    {
        $comment = Achievement::where('user_id', $userId)->where('type', 'comments')->latest()->first();
        if (!$comment) {
            return;
        }
        return $this->getNextRecord('comments', 'written_comment', $comment->achievements_key);
    }

    /**
     * get badge of user function
     *
     * @param [type] $userId
     * @return void
     */

    public function getBadge($userId, $badgeType = 'current')
    {
        $badge = Achievement::where('user_id', $userId)->where('type', 'badges')->latest()->first();
        if($badge){
            $key = $badge->achievements_key;
        } else {
            $key =  0 ;
            if($badgeType == 'current'){
                return config("constant.badges.0.msg");
            }
        }
        if($badgeType == 'current'){
            $result = collect(config("constant.badges"));
            $currentData = $result->where('achievements', $key)->keys()->first();
            return $result[$currentData]['msg'];
        } else {
            return $this->getNextRecord('badges', 'achievements', $key);
        }
    }

    /**
     * get next record of achievements
     *
     * @param [type] $type
     * @param [type] $key
     * @param [type] $value
     * @return object
     */
    public function getNextRecord($type, $key, $value)
    {
        $configData = collect(config("constant.{$type}"));
        $result = $configData->where($key, $value)->keys()->first();
        return isset($configData[$result + 1]) ? $configData[$result + 1]['msg'] : '';
    }

    /**
     * get remaining achievements of user function
     *
     * @param [type] $userId
     * @return void
     */
    public function getRemainingAchievements($userID,$msg)
    {
        $achievements = Achievement::where('user_id', $userID)->where('type', '<>', 'badges')->count();
        $result = collect(config("constant.badges"))->where('msg', $msg)->first();
        return !empty($result) ? $result['achievements'] - $achievements : '';
    }

}
