<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::apiResource('/documents', DocumentController::class);
Route::apiResource('/comments', CommentController::class);
Route::apiResource('/students', StudentController::class);



// Route::middleware('auth:student')->group(function () {
//     Route::get('/student/profile', function (Request $request) {
//         return $request->user(); // retourne le Student connecté
//     });

//     Route::post('/student/logout', [StudentController::class, 'logout']);
// });

Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
Route::post('/student/login', [AuthController::class, 'loginStudent']);

Route::middleware('auth:sanctum')->post('/admin/logout', [AuthController::class, 'logout']);
Route::middleware('auth:student')->post('/student/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/admin/update-password', [AuthController::class, 'updatePasswordAdmin']);
Route::middleware('auth:student')->post('/student/update-password', [AuthController::class, 'updatePasswordStudent']);