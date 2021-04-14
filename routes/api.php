<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$exceptCreateAndEdit = [
    'except' => ['create', 'edit'],
];
Route::resource('categories', 'CategoryController', $exceptCreateAndEdit);
Route::resource('genres', 'GenreController', $exceptCreateAndEdit);
