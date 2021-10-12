<?php

namespace App\Http\Controllers;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    public function index(Request $request)
    {
        // check any user exists or not
        $users = User::count();
        if (!$users) {

            // Add dummy data of user
            User::factory()->create();
        }
        $input = $request->all();
        $id = ($input['user_id']) ? $input['user_id'] : 1;
        $lesson_id = ($input['lesson_id']) ? $input['lesson_id'] : 1;
        $user = User::find($id);
        $lesson = Lesson::find($lesson_id);
        if (!$lesson) {
            $lessonWatched = new LessonUser();
            $lessonWatched->user_id = 1;
            $lessonWatched->lesson_id = 1;
            $lessonWatched->watched = 1;
            $lessonWatched->save();
        }
        LessonWatched::dispatch($lesson, $user);

        return response()->json([
            'success' => true,
            'message' => 'Lesson watched successfully',
            'data' => $lesson
        ], 200);
    }
}
