<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LessonsController;

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);
Route::get('/add-comment', [CommentsController::class, 'index']);
Route::get('/watch-lesson', [LessonsController::class, 'index']);
