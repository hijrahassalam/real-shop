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

// Checkout - requires authentication
Route::view('checkout', 'checkout.index')
    ->middleware(['auth'])
    ->name('checkout');

// Orders - requires authentication
Route::view('orders', 'orders.index')
    ->middleware(['auth'])
    ->name('orders');

require __DIR__ . '/auth.php';
