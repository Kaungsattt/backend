<?php

use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/students', 'students.index')->name('students.page');

Route::get('/test', function () {
    return response()->json(['message ' => 'this is testing']);
});

// SchoolClass Route
Route::prefix('/schoolClass')->group(function () {
    Route::get('/index', [SchoolClassController::class, 'index'])->name('schoolClass.index');
});

// Student Route
Route::prefix('/student')->group(function () {
    Route::view('/index', 'students.index')->name('students.index');
    Route::get('/create', [StudentsController::class, 'create'])->name('students.create');
    Route::post('/store', [StudentsController::class, 'webStore'])->name('students.store');
});
