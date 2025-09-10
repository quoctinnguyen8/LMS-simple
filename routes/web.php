<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Admin\RoomBookingDetailController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SitemapController;

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
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/registration/', [CourseController::class, 'course_registration'])->name('courses.registration');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::post('/rooms/bookings', [RoomController::class, 'roomBookings'])->name('rooms.bookings');
Route::get('/contacts', [HomeController::class, 'contacts'])->name('contacts');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/news/category/{slug}', [App\Http\Controllers\NewsController::class, 'category'])->name('news.category');
Route::get('/news/{slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Admin routes for room booking details 
Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::post('/room-booking-details/{id}/reject', [RoomBookingDetailController::class, 'reject'])->name('admin.room-booking-details.reject');
    Route::post('/room-booking-details/{id}/cancel', [RoomBookingDetailController::class, 'cancel'])->name('admin.room-booking-details.cancel');
});
// Sitemap route
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Login route để redirect về trang chủ
Route::get('/login', function () {
    return redirect('/');
})->name('login');
