<?php

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
    return view('welcome');
});

//Route::middleware([
//    'auth:sanctum',
//    config('jetstream.auth_session'),
//    'verified',
//])->group(function () {
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('dashboard');
//});


Route::get('localization/{locale}',\App\Http\Controllers\Localization::class)->name('localization');
Route::get('/{record}/pdf/dwonload',[\App\Http\Controllers\DownloadBdfController::class,'generateInvoice'])->name('student.pdf.dwonload');
Route::get('/{record}/pdf/viewInvoice',[\App\Http\Controllers\DownloadBdfController::class,'viewInvoice'])->name('student.pdf.view');
Route::get('/{record}/pdf/viewApplication',[\App\Http\Controllers\DownloadBdfController::class,'ViewApplication'])->name('ViewApplication');
Route::get('/getFilter',[\App\Http\Controllers\TestFillterController::class,'index']);
Route::middleware(\App\Http\Middleware\Localization::class)
    ->group(function () {
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboard');
            })->name('dashboard');
        });
    });
//Route::post('/login', [\App\Http\Controllers\AuthController::class,'login'])->name('login');
//Route::post('/register', [\App\Http\Controllers\AuthController::class,'register'])->name('register');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/request',\App\Livewire\ListRequests::class)->name('request');
    // Other protected routes
});
