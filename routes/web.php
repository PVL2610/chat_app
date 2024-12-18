<?php

use App\Events\MessageSent;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::get('/test-event', function () {
    $message = (object) ['to' => 1, 'content' => 'Hello, World!']; // Example message
    broadcast(new MessageSent($message)); // Trigger the event
    return response()->json(['status' => 'Event broadcasted successfully']);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/{id}', [ChatController::class, 'selectUser'])->name('chat.user');
    Route::post('/send/message', [ChatController::class, 'sendMessage'])->name('send.message');
    Route::get('/chat-users', [UserController::class, 'getChatUsers'])->name('chat-user-list');


});

require __DIR__.'/auth.php';
