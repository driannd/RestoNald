<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BooktableController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/welcome', function() {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::get('/', function () {
    return view('imah');
});

Route::get('/chef', function () {
    return view('chef');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});


Route::get('/booking', [BooktableController::class, 'reservasi'])->name('booking')->middleware('auth');




Route::get('/rese', [BooktableController::class, 'index'])->name('reservations')->middleware('auth');

Route::post('/reservations', [BooktableController::class, 'bookTable'])->name('bookTable');


Route::get('/dashboard', [MenuController::class, 'index'])->name('dashboard')->middleware('auth');

// Tambah Berita
Route::get('/menu/create', [MenuController::class, 'create'])->name('adm.menu.create');
Route::post('/menu', [MenuController::class, 'store'])->name('adm.menu.store');

// Edit Berita
Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('adm.menu.edit');
Route::put('/menu/{id}', [MenuController::class, 'update'])->name('adm.menu.update');

// Hapus Berita
Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('adm.menu.destroy');


Route::get('/chef', [ChefController::class, 'index'])->name('chef')->middleware('auth');

// Tambah Chef
Route::get('/chef/create', [ChefController::class, 'create'])->name('adm.chef.create');
Route::post('/chef', [ChefController::class, 'store'])->name('adm.chef.store');

// Edit Chef
Route::get('/chef/{id}/edit', [ChefController::class, 'edit'])->name('adm.chef.edit');
Route::put('/chef/{id}', [ChefController::class, 'update'])->name('adm.chef.update');

// Hapus Chef
Route::delete('/chef/{id}', [ChefController::class, 'destroy'])->name('adm.chef.destroy');
Route::get('/', [HomeController::class, 'imah'])->name('imah');