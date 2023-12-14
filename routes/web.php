<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ChangesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncidentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\TicketsController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function (){

    Route::controller(TicketsController::class)->group(function (){
        Route::get('/tickets', 'index')->name('tickets.index');
        Route::get('/tickets/create/{type?}', 'create')->name('tickets.create');
        Route::get('/tickets/{id}', 'edit')->name('tickets.edit');
    });

    Route::controller(RequestsController::class)->group(function (){
        Route::resource('/requests', RequestsController::class)->only(['index', 'create', 'edit']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
