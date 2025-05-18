<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::apiResource('/students', StudentController::class);

// Admin

Route::apiResource('/admins', AdminController::class)->only([
    'index', 'store', 'show', 'update', 'destroy'
]);

Route::middleware('auth:sanctum')->prefix('/admin')->group(function () {

    // Étudiants
    Route::get('/students', [AdminController::class, 'getAllStudents']);
    Route::delete('/students/{id}', [AdminController::class, 'deleteStudent']);

    // Documents
    Route::get('/documents', [AdminController::class, 'getAllDocuments']);
    Route::put('/documents/{id}/validate', [AdminController::class, 'validateDocument']);
    Route::put('/documents/{id}/reject', [AdminController::class, 'rejectDocument']);
    Route::put('/documents/{id}', [AdminController::class, 'updateDocument']);
    Route::delete('/documents/{id}', [AdminController::class, 'deleteDocument']);
});


// Documents 

Route::apiResource('/documents', DocumentController::class)->only(['index', 'show']);
    
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/documents', DocumentController::class)->only(['store', 'update', 'destroy']);
    Route::get('/documents/{id}/download', [DocumentController::class, 'download']);
});

// Comments

Route::apiResource('/comments', CommentController::class)->middleware('auth:sanctum');

// Authentication

Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/student/login', [AuthController::class, 'studentLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/admin/update-password', [AuthController::class, 'updatePasswordAdmin']);
    Route::put('/student/update-password', [AuthController::class, 'updatePasswordStudent']);
});