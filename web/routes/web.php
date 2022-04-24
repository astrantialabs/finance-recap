<?php

use App\Http\Livewire\App\Index;
use Illuminate\Support\Facades\Route;

Route::get("/login", \App\Http\Livewire\Auth\Login::class);
Route::get("/register", \App\Http\Livewire\Auth\Register::class);

Route::group(["middleware" => "auth"], function () {
    Route::get("/", \App\Http\Livewire\App\Index::class);
    Route::get("/{id}", \App\Http\Livewire\App\HandleDivision::class);
});
