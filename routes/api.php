<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::apiResource('/students', StudentController::class);
Route::apiResource('/admins', AdminController::class);

Route::apiResource('/comments', CommentController::class);

Route::apiResource('/documents', DocumentController::class)->only(['index', 'show']);
    
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/documents', DocumentController::class)->only(['store', 'update', 'destroy']);
    Route::get('/documents/{id}/download', [DocumentController::class, 'download']);
});



 // Authetication

Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/student/login', [AuthController::class, 'studentLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/admin/update-password', [AuthController::class, 'updatePasswordAdmin']);
    Route::put('/student/update-password', [AuthController::class, 'updatePasswordStudent']);
});