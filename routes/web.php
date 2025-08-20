<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\NodoController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// RUTAS PARA EL ADMIN
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');

// RUTAS PARA EL ADMIN - USUARIOS
Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth');
Route::get('/admin/usuarios/create', [UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth');
Route::post('/admin/usuarios/create', [UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');
Route::get('/admin/usuarios/{id}', [UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth');
Route::get('/admin/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth');
Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth');
Route::get('/admin/usuarios/{id}/confirm-delete', [UsuarioController::class, 'confirmDelete'])->name('admin.usuarios.confirm-delete')->middleware('auth');
Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');

// RUTAS PARA EL ADMIN - NODOS
Route::get('/admin/nodos', [NodoController::class, 'index'])->name('admin.nodos.index')->middleware('auth');
Route::get('/admin/nodos/create', [NodoController::class, 'create'])->name('admin.nodos.create')->middleware('auth');
Route::post('/admin/nodos/create', [NodoController::class, 'store'])->name('admin.nodos.store')->middleware('auth');
Route::get('/admin/nodos/{id}', [NodoController::class, 'show'])->name('admin.nodos.show')->middleware('auth');
Route::get('/admin/nodos/{id}/edit', [NodoController::class, 'edit'])->name('admin.nodos.edit')->middleware('auth');
Route::put('/admin/nodos/{id}', [NodoController::class, 'update'])->name('admin.nodos.update')->middleware('auth');
Route::get('/admin/nodos/{id}/confirm-delete', [NodoController::class, 'confirmDelete'])->name('admin.nodos.confirm-delete')->middleware('auth');
Route::delete('/admin/nodos/{id}', [NodoController::class, 'destroy'])->name('admin.nodos.destroy')->middleware('auth');

// RUTAS PARA EL ADMIN - SOCIOS
Route::get('/admin/socios', [SocioController::class, 'index'])->name('admin.socios.index')->middleware('auth');
Route::get('/admin/socios/create', [SocioController::class, 'create'])->name('admin.socios.create')->middleware('auth');
Route::post('/admin/socios/create', [SocioController::class, 'store'])->name('admin.socios.store')->middleware('auth');
Route::get('/admin/socios/{id}', [SocioController::class, 'show'])->name('admin.socios.show')->middleware('auth');
Route::get('/admin/socios/{id}/edit', [SocioController::class, 'edit'])->name('admin.socios.edit')->middleware('auth');
Route::put('/admin/socios/{id}', [SocioController::class, 'update'])->name('admin.socios.update')->middleware('auth');
Route::get('/admin/socios/{id}/confirm-delete', [SocioController::class, 'confirmDelete'])->name('admin.socios.confirm-delete')->middleware('auth');
Route::delete('/admin/socios/{id}', [SocioController::class, 'destroy'])->name('admin.socios.destroy')->middleware('auth');

// RUTAS PARA EL ADMIN - CLIENTES
Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('admin.clientes.index')->middleware('auth');
Route::get('/admin/clientes/create', [ClienteController::class, 'create'])->name('admin.clientes.create')->middleware('auth');
Route::post('/admin/clientes/create', [ClienteController::class, 'store'])->name('admin.clientes.store')->middleware('auth');
Route::get('/admin/clientes/{id}', [ClienteController::class, 'show'])->name('admin.clientes.show')->middleware('auth');
Route::get('/admin/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('admin.clientes.edit')->middleware('auth');
Route::put('/admin/clientes/{id}', [ClienteController::class, 'update'])->name('admin.clientes.update')->middleware('auth');
Route::get('/admin/clientes/{id}/confirm-delete', [ClienteController::class, 'confirmDelete'])->name('admin.clientes.confirm-delete')->middleware('auth');
Route::delete('/admin/clientes/{id}', [ClienteController::class, 'destroy'])->name('admin.clientes.destroy')->middleware('auth');

// RUTAS PARA EL ADMIN - OPERACIONES


//RUTA PARA BUSCAR LAS LOCALIDADES SEGÚN LA PROVINCIA Y LA LOCALIDAD
Route::get('/admin/localidades/{idProv}', [LocalidadController::class, 'getLocalidades']);
Route::get('/admin/codpostales/{idLocal}', [LocalidadController::class, 'getCodigosPostales']);
