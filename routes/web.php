<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\NewsController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/khoa-hoc', [CourseController::class, 'index'])->name('courses.index');
Route::get('/khoa-hoc/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/khoa-hoc/danh-muc/{slug}', [CourseController::class, 'category'])->name('courses.category');
Route::post('/khoa-hoc/dang-ky', [CourseController::class, 'course_registration'])->name('courses.registration');
Route::get('/phong-hoc', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/phong-hoc/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::post('/phong-hoc/dat-phong', [RoomController::class, 'roomBookings'])->name('rooms.bookings');
Route::get('/lien-he', [HomeController::class, 'contacts'])->name('contacts');
Route::get('/tin-tuc', [NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/danh-muc/{slug}', [NewsController::class, 'category'])->name('news.category');
Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('news.show');
