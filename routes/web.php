<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Volt::route('questions', 'questions.list')
        ->name('questions');

    Volt::route('questions/create', 'questions.form')
        ->name('questions.create');

    Volt::route('questions/{question}', 'questions.form')
        ->name('questions.edit');
});

require __DIR__.'/auth.php';
