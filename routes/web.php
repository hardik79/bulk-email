<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\MailController;
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

Route::get('/', function () {
    return redirect()->to('/send-mail');
});
Route::get('send-mail', [MailController::class, 'showForm']);
Route::post('send-mail', [MailController::class, 'sendMail']);
Route::post('/save-credentials', [EmailController::class, 'saveCredentials']);
Route::post('set-credentials', [MailController::class, 'setCredentials']);
