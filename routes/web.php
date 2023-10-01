<?php

use App\Http\Controllers\ChangesController;
use App\Http\Controllers\IncidentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\TicketsController;
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

Route::get('/', function (){
   return view('index');
})->name('default');

Route::resource('/tickets', TicketsController::class)->only('index', 'show');
Route::resource('/incidents', IncidentsController::class)->only('create');
Route::resource('/requests', RequestsController::class)->only('create');
Route::resource('/changes', ChangesController::class)->only('create');

require __DIR__.'/auth.php';
