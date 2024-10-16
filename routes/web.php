<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/book/{id}',[HomeController::class,'detail'])->name('book.detail');
Route::post('/save-book-review',[HomeController::class,'savereview'])->name('book.savereview');


Route::prefix('account')->group(function () {
Route::group(['middleware' => 'guest'], function(){

Route::get('register', [AccountController::class, 'register'])
    ->name('account.register');

Route::post('register', [AccountController::class, 'processregister'])
    ->name('account.processregister');

Route::get('login', [AccountController::class, 'login'])
    ->name('account.login');

Route::post('login', [AccountController::class, 'authenticate'])
    ->name('account.authenticate');


});

Route::group(['middleware' => 'auth'], function(){

Route::get('profile', [AccountController::class, 'profile'])
    ->name('account.profile');

Route::get('logout', [AccountController::class, 'logout'])
    ->name('account.logout');

Route::post('updateprofile', [AccountController::class, 'updateprofile'])
    ->name('account.updateprofile');

    // custome middlware is used here
//Books related routes

    Route::group(['middleware' => 'check-admin'], function(){
        Route::get('books', [BookController::class, 'index'])
        ->name('books.index');

    Route::get('books/create', [BookController::class, 'create'])
        ->name('books.create');

    Route::post('books', [BookController::class, 'store'])
        ->name('books.store');

    Route::get('books/edit/{id}', [BookController::class, 'edit'])
        ->name('books.edit');

    Route::post('books/edit/{id}', [BookController::class, 'update'])
        ->name('books.update');

    Route::delete('books/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    });

    //review
Route::get('reviews', [ReviewController::class, 'index'])
->name('account.review');
Route::get('reviews/{id}', [ReviewController::class, 'edit'])
->name('account.review.edit');
Route::post('reviews/{id}', [ReviewController::class, 'updatereview'])
->name('account.review.updatereview');
Route::post('delete-review', [ReviewController::class, 'deletereview'])
->name('account.review.deletereview');




Route::get('myreviews',[AccountController::class,'myreviews'])
->name('account.myreviews');

Route::get('myreviews/{id}/', [AccountController::class, 'editreview'])
    ->name('account.myreviews.editmyreview');

Route::post('myreviews/{id}/', [AccountController::class, 'updatereview'])
    ->name('account.myreviews.updatereview');



});
});
