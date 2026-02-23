<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', 'pages::dashboard')->name('dashboard');
    Route::livewire('clients', 'pages::clients.list-client')->name('clients.index');
    Route::livewire('clients/create', 'pages::clients.create-client')->name('clients.create');
    Route::livewire('clients/{client}', 'pages::clients.show-client')->name('clients.show');
});

require __DIR__.'/settings.php';
