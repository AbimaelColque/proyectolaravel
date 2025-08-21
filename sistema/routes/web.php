<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('productos', App\Http\Controllers\ProductoController::class);
Route::resource('categorias', App\Http\Controllers\CategoriaController::class);
Route::resource('proveedores', App\Http\Controllers\ProveedoreController::class);
Route::resource('compras', App\Http\Controllers\CompraController::class);

Route::resource('clientes', App\Http\Controllers\ClienteController::class);
Route::resource('ventas', App\Http\Controllers\VentaController::class);
