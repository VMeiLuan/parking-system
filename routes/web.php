<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/login', function () {
    return view('pages.user_login'); 
})->name('login');

Route::get('/register', function () {
    return view('pages.user_registration'); 
})->name('register');

Route::get('/logout', function () {
    return view('pages.logout'); 
})->name('logout');

Route::get('/logout', function () {
    return view('pages.logout'); 
})->name('logout');

// admin route
Route::get('/admin/area-setting', function () {
    return view('pages.area_setting'); 
})->name('area');

Route::get('/admin/parking-rate-setting', function () {
    return view('pages.parking_rate'); 
})->name('parking-rate');

Route::get('/admin/exception-rate-setting', function () {
    return view('pages.exception_rate'); 
})->name('exception-rate');

Route::get('/admin/users', function () {
    return view('pages.users'); 
})->name('users');

// user reoute
Route::get('/parking-system', function () {
    return view('pages.user_park'); 
})->name('parking-system');


// Route::middleware(['auth', 'restrict.admin'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
//     // other admin routes...
// });
