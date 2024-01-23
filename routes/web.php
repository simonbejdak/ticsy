<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncidentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\ResolverPanelController;
use App\Http\Controllers\TasksController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/resolver/panel/incidents', [ResolverPanelController::class, 'incidents'])->name('resolver-panel.incidents');

Route::middleware('auth')->group(function (){

    Route::resource('/incidents', IncidentsController::class)->only(['index', 'create', 'edit']);
    Route::resource('/requests', RequestsController::class)->only(['index', 'create', 'edit']);
    Route::resource('/tasks', TasksController::class)->only(['edit']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
