<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::view('/', 'welcome');
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/users', 'UsersController')->except(['show'])->middleware(['auth']);
Route::resource('/posts', 'PostsController')->except(['show'])->middleware(['auth']);
Route::get('/supervisors', 'SupervisorsController')->middleware(['auth'])->name('supervisors.index');
