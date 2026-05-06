<?php

use App\Http\Controllers\Api\MovieApiController;
use Illuminate\Support\Facades\Route;

Route::get('/movies', [MovieApiController::class, 'index'])->name('api.movies.index');
Route::get('/movies/{movie}', [MovieApiController::class, 'show'])->name('api.movies.show');
