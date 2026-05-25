<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiClientController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
   Route::get('/users', [UserController::class, 'index'])
    ->middleware('permission:view users')
    ->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])
    ->middleware('permission:create users')
    ->name('users.create');
    Route::post('/users', [UserController::class, 'store'])
    ->middleware('permission:create users')
    ->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->middleware('permission:edit users')
    ->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware('permission:edit users')
    ->name('users.update');
    Route::get('/clients', [ApiClientController::class, 'index'])
    ->middleware('permission:view clients')
    ->name('clients.index');
    Route::get('/clients/create', [ApiClientController::class, 'create'])
    ->middleware('permission:create clients')
    ->name('clients.create');
    Route::post('/clients', [ApiClientController::class, 'store'])
    ->middleware('permission:create clients')
    ->name('clients.store');
    Route::get('/clients/{client}', [ApiClientController::class, 'show'])
    ->middleware('permission:view clients')
    ->name('clients.show');
    Route::patch('/clients/{client}/status', [ApiClientController::class, 'updateStatus'])
    ->middleware('permission:disable clients')
    ->name('clients.update-status');

    Route::get('/messages', [MessageController::class, 'index'])
    ->middleware('permission:view messages')
    ->name('messages.index');
    Route::get('/messages/{message}', [MessageController::class, 'show'])
    ->middleware('permission:view messages')
    ->name('messages.show');

      Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:view roles')
        ->name('roles.index');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->middleware('permission:create roles')
        ->name('roles.create');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:create roles')
        ->name('roles.store');

    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
        ->middleware('permission:edit roles')
        ->name('roles.edit');

    Route::patch('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:edit roles')
        ->name('roles.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
