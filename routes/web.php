<?php

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

Route::resource('/tickets', TicketsController::class)->middleware(Authenticate::class)->only(['index', 'store', 'update']);

Route::get('/tickets/create/{type?}', [TicketsController::class, 'create'])->name('tickets.create')
    ->middleware(Authenticate::class);
Route::get('/tickets/{id}', [TicketsController::class, 'edit'])->name('tickets.edit')
    ->middleware(Authenticate::class);
Route::patch('/tickets/{id}/set/priority', [TicketsController::class, 'setPriority'])->name('tickets.set-priority')
    ->middleware(Authenticate::class);
Route::patch('/tickets/{id}/set/resolver', [TicketsController::class, 'setResolver'])->name('tickets.set-resolver')
    ->middleware(Authenticate::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
