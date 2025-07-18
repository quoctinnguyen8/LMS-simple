<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/category/{slug}', [CourseController::class, 'category'])->name('courses.category');
Route::post('/courses/registration/', [CourseController::class, 'course_registration'])->name('courses.registration');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::post('/rooms/bookings', [RoomController::class, 'roomBookings'])->name('rooms.bookings');
Route::get('/contacts', [HomeController::class, 'contacts'])->name('contacts');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/news/category/{slug}', [App\Http\Controllers\NewsController::class, 'category'])->name('news.category');
Route::get('/news/{slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
