<?php

use Illuminate\Support\Facades\Route;

// O frontend é uma página estática em JavaScript puro (public/index.html).
Route::get('/', fn () => response()->file(public_path('index.html')));
