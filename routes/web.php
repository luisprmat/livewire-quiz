<?php

use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResultController;
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

Route::get('/', [HomeController::class, 'index'])
    ->name('home');
Route::get('quiz/{quiz}/{slug?}', [HomeController::class, 'show'])
    ->name('quiz.show');
Route::get('results/{test}', [ResultController::class, 'show'])
    ->name('results.show');
Volt::route('leaderboard', 'front.leaderboard')
    ->name('leaderboard');

Route::middleware('auth')->group(function () {
    Route::get('results', [ResultController::class, 'index'])
        ->name('results.index');

    Route::view('profile', 'profile')
        ->name('profile');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Volt::route('questions', 'questions.list')
        ->name('questions');

    Volt::route('questions/create', 'questions.form')
        ->name('questions.create');

    Volt::route('questions/{question}', 'questions.form')
        ->name('questions.edit');

    Volt::route('quizzes', 'quizzes.list')
        ->name('quizzes');

    Volt::route('quizzes/create', 'quizzes.form')
        ->name('quizzes.create');

    Volt::route('quizzes/{quiz}', 'quizzes.form')
        ->name('quizzes.edit');

    Route::get('tests', TestController::class)
        ->name('tests');
});

require __DIR__.'/auth.php';
