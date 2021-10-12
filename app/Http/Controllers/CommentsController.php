<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(Request $request){

        // check any user exists or not
        $users = User::count();
        if (!$users) {

            // Add dummy data of user
            User::factory()->create();
        }
        $input = $request->all();
        $id = ($input['user_id']) ? $input['user_id'] : 1;
        $comment = Comment::find($id);
        if (!$comment) {
            $comment = new Comment;
            $comment->body = ($input['comment']) ? $input['comment'] : "Test comment";
            $comment->user_id = 1;
        }
        CommentWritten::dispatch($comment);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 200);
    }
}
