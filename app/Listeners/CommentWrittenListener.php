<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Comment;

class CommentWrittenListener
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
     * @param  CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        $comment = new Comment();
        $comment->body = $event->comment->body;
        $comment->user_id = $event->comment->user_id;
        $comment->save();

        $commentCounts = Comment::where('user_id', $comment->user_id)->count();
        $commentMsg = collect(config("constant.comments"))->where('written_comment', $commentCounts)->first();

        if($commentMsg){
            AchievementUnlocked::dispatch($commentCounts, 'comments', $comment->user);
        }
    }
}
