<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRequestController;
use App\Http\Controllers\UserController;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// Rutas protegidas (cualquier usuario autenticado)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Un usuario puede actualizar su propio perfil; UserController::update
    // impide que se cambie a sí mismo el role/status salvo que sea admin
    Route::put('/users/{id}', [UserController::class, 'update']);

    // Solicitudes de cursos del propio estudiante
    Route::get('/requests/my', [CourseRequestController::class, 'myRequests']);
    Route::post('/requests', [CourseRequestController::class, 'store']);

    // Rutas de administración (requieren role=admin, ver middleware EnsureUserIsAdmin)
    Route::middleware('admin')->group(function () {
        // Administración de usuarios
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Administración de cursos
        Route::get('/admin/courses', [CourseController::class, 'indexAdmin']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

        // Solicitudes de cursos (vista de administración)
        Route::get('/admin/requests', [CourseRequestController::class, 'index']);
        Route::put('/requests/{id}/status', [CourseRequestController::class, 'updateStatus']);
    });
});
