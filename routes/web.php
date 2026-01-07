<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Products - accessible to everyone
Route::view('products', 'products.index')->name('products');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Cart - requires authentication
Route::view('cart', 'cart.index')
    ->middleware(['auth'])
    ->name('cart');

require __DIR__ . '/auth.php';
