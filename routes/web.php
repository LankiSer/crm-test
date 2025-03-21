<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatMessageController;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Contacts
    Route::resource('contacts', ContactController::class);

    // Companies
    Route::resource('companies', CompanyController::class);

    // Deals
    Route::resource('deals', DealController::class);

    // Tasks
    Route::resource('tasks', TaskController::class);

    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chatRoom}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'createRoom'])->name('chat.store');
    Route::post('/chat/{chatRoom}/messages', [ChatMessageController::class, 'store'])->name('chat.messages.store');
    Route::post('/chat/{chatRoom}/read', [ChatMessageController::class, 'markAsRead'])->name('chat.messages.read');
    Route::post('/chat/create', [ChatController::class, 'createRoom'])->name('chat.create');
    Route::post('/chat/{chatRoom}/message', [ChatController::class, 'sendMessage'])->name('chat.message.send');
    Route::post('/chat/{chatRoom}/members', [ChatController::class, 'addMembers'])->name('chat.member.add');
    Route::post('/chat/{chatRoom}/leave', [ChatController::class, 'leaveGroup'])->name('chat.leave');
    Route::delete('/chat/{chatRoom}', [ChatController::class, 'deleteChat'])->name('chat.delete');

    // Reports and Settings routes
    Route::get('/reports', function() {
        return view('reports.index');
    })->name('reports');
    
    Route::get('/settings', function() {
        return view('settings.index');
    })->name('settings');
});
