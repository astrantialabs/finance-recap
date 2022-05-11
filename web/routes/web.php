<?php

use Illuminate\Support\Facades\Route;

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


Route::get("/login", \App\Http\Livewire\Auth\Login::class);
Route::get("/logout", \App\Http\Livewire\Auth\Logout::class);

Route::get("/download", [\App\Http\Livewire\Dashboard\Index::class, "export"]);

Route::group(["middleware" => "auth"], function () {
    Route::get("/", \App\Http\Livewire\Dashboard\Index::class);
    
    Route::group(["middleware" => ["role:superadmin"]], function () {
        Route::get("/register", \App\Http\Livewire\Auth\Register::class);
        Route::get("/users", \App\Http\Livewire\Users\Index::class);
        Route::get("/users/edit/{id}", \App\Http\Livewire\Users\Edit::class);
    });

    Route::get("/{id}", \App\Http\Livewire\Dashboard\Division::class);
});
