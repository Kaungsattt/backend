<?php

use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// testing route for api
// Route::get('/ping', function () {
//    return response()->json(['ok' => true]);
// });

Route::get('/school-classes', [SchoolClassController::class, 'apiIndex'])->name('api.school-classes.index');
Route::apiResource('/students', StudentsController::class)->names('api.students');
