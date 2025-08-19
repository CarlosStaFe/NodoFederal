<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// RUTAS PARA EL ADMIN
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')
    ->middleware('auth');

// RUTAS PARA EL ADMIN - USUARIOS
Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth');
Route::get('/admin/usuarios/create', [UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth');
Route::post('/admin/usuarios/create', [UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');
Route::get('/admin/usuarios/{id}', [UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth');
Route::get('/admin/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth');
Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth');
Route::get('/admin/usuarios/{id}/confirm-delete', [UsuarioController::class, 'confirmDelete'])->name('admin.usuarios.confirm-delete')->middleware('auth');
Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');