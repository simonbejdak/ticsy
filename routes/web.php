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

Route::resource('/tickets', TicketsController::class)->middleware(Authenticate::class);

Route::get('/tickets/create/{type}', [TicketsController::class, 'create'])->name('tickets.create');
Route::get('/tickets/edit/{id}', [TicketsController::class, 'edit'])->name('tickets.edit');

require __DIR__.'/auth.php';
