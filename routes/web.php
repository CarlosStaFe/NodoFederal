<?php

use Illuminate\Support\Facades\Route;
// Endpoint AJAX para calcular CUIT
Route::post('/admin/clientes/calcular-cuit', [\App\Http\Controllers\ClienteController::class, 'calcularCuit'])->name('clientes.calcular-cuit');

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LocalidadController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\NodoController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OperacionController;

Route::get('/', function () {
    return redirect()->route('admin.index');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// RUTAS PARA EL ADMIN
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');

// RUTAS PARA EL ADMIN - USUARIOS
Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware(['auth', 'can:admin.usuarios.index']);
Route::get('/admin/usuarios/create', [UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware(['auth', 'can:admin.usuarios.create']);
Route::post('/admin/usuarios/create', [UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware(['auth', 'can:admin.usuarios.store']);
Route::get('/admin/usuarios/{id}', [UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware(['auth', 'can:admin.usuarios.show']);
Route::get('/admin/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware(['auth', 'can:admin.usuarios.edit']);
Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware(['auth', 'can:admin.usuarios.update']);
Route::get('/admin/usuarios/{id}/confirm-delete', [UsuarioController::class, 'confirmDelete'])->name('admin.usuarios.confirm-delete')->middleware(['auth', 'can:admin.usuarios.confirm-delete']);
Route::delete('/admin/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware(['auth', 'can:admin.usuarios.destroy']);

// RUTAS PARA EL ADMIN - NODOS
Route::get('/admin/nodos', [NodoController::class, 'index'])->name('admin.nodos.index')->middleware(['auth', 'can:admin.nodos.index']);
Route::get('/admin/nodos/create', [NodoController::class, 'create'])->name('admin.nodos.create')->middleware(['auth', 'can:admin.nodos.create']);
Route::post('/admin/nodos/create', [NodoController::class, 'store'])->name('admin.nodos.store')->middleware(['auth', 'can:admin.nodos.store']);
Route::get('/admin/nodos/{id}', [NodoController::class, 'show'])->name('admin.nodos.show')->middleware(['auth', 'can:admin.nodos.show']);
Route::get('/admin/nodos/{id}/edit', [NodoController::class, 'edit'])->name('admin.nodos.edit')->middleware(['auth', 'can:admin.nodos.edit']);
Route::put('/admin/nodos/{id}', [NodoController::class, 'update'])->name('admin.nodos.update')->middleware(['auth', 'can:admin.nodos.update']);
Route::get('/admin/nodos/{id}/confirm-delete', [NodoController::class, 'confirmDelete'])->name('admin.nodos.confirm-delete')->middleware(['auth', 'can:admin.nodos.confirm-delete']);
Route::delete('/admin/nodos/{id}', [NodoController::class, 'destroy'])->name('admin.nodos.destroy')->middleware(['auth', 'can:admin.nodos.destroy']);

// RUTAS PARA EL ADMIN - SOCIOS
Route::get('/admin/socios', [SocioController::class, 'index'])->name('admin.socios.index')->middleware(['auth', 'can:admin.socios.index']);
Route::get('/admin/socios/create', [SocioController::class, 'create'])->name('admin.socios.create')->middleware(['auth', 'can:admin.socios.create']);
Route::post('/admin/socios/create', [SocioController::class, 'store'])->name('admin.socios.store')->middleware(['auth', 'can:admin.socios.store']);
Route::get('/admin/socios/{id}', [SocioController::class, 'show'])->name('admin.socios.show')->middleware(['auth', 'can:admin.socios.show']);
Route::get('/admin/socios/{id}/edit', [SocioController::class, 'edit'])->name('admin.socios.edit')->middleware(['auth', 'can:admin.socios.edit']);
Route::put('/admin/socios/{id}', [SocioController::class, 'update'])->name('admin.socios.update')->middleware(['auth', 'can:admin.socios.update']);
Route::get('/admin/socios/{id}/confirm-delete', [SocioController::class, 'confirmDelete'])->name('admin.socios.confirm-delete')->middleware(['auth', 'can:admin.socios.confirm-delete']);
Route::delete('/admin/socios/{id}', [SocioController::class, 'destroy'])->name('admin.socios.destroy')->middleware(['auth', 'can:admin.socios.destroy']);
Route::get('/admin/socios/buscar-por-numero/{numero}', [SocioController::class, 'buscarPorNumero'])->name('socios.buscar-por-numero')->middleware(['auth', 'can:admin.socios.destroy']);

// RUTAS PARA EL ADMIN - CLIENTES
Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('admin.clientes.index')->middleware(['auth', 'can:admin.clientes.index']);
Route::get('/admin/clientes/create', [ClienteController::class, 'create'])->name('admin.clientes.create')->middleware(['auth', 'can:admin.clientes.create']);
Route::post('/admin/clientes/create', [ClienteController::class, 'store'])->name('admin.clientes.store')->middleware(['auth', 'can:admin.clientes.store']);
Route::get('/admin/clientes/{id}', [ClienteController::class, 'show'])->name('admin.clientes.show')->middleware(['auth', 'can:admin.clientes.show']);
Route::get('/admin/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('admin.clientes.edit')->middleware(['auth', 'can:admin.clientes.edit']);
Route::put('/admin/clientes/{id}', [ClienteController::class, 'update'])->name('admin.clientes.update')->middleware(['auth', 'can:admin.clientes.update']);
Route::get('/admin/clientes/{id}/confirm-delete', [ClienteController::class, 'confirmDelete'])->name('admin.clientes.confirm-delete')->middleware(['auth', 'can:admin.clientes.confirm-delete']);
Route::delete('/admin/clientes/{id}', [ClienteController::class, 'destroy'])->name('admin.clientes.destroy')->middleware(['auth', 'can:admin.clientes.destroy']);
// Endpoint AJAX para buscar cliente por CUIT (sin middleware, antes de rutas protegidas)
Route::get('/admin/clientes/buscar-por-cuit/{cuit}', [ClienteController::class, 'buscarPorCuit'])->name('admin.clientes.buscar-por-cuit')->middleware(['auth', 'can:admin.clientes.buscar-por-cuit']);

// RUTAS PARA EL ADMIN - OPERACIONES
Route::get('/admin/operaciones/consultar', [OperacionController::class, 'consultar'])->name('admin.operaciones.consulta')->middleware(['auth', 'can:admin.operaciones.consultar']);
Route::get('/admin/operaciones/informe', [OperacionController::class, 'informe'])->name('admin.operaciones.informe')->middleware(['auth', 'can:admin.operaciones.informe']);
Route::get('/admin/operaciones/pdf', [OperacionController::class, 'pdf'])->name('admin.operaciones.pdf')->middleware(['auth', 'can:admin.operaciones.pdf']);
// Consulta de operaciones por documento (API externa)
Route::post('/admin/operaciones/consultar', [OperacionController::class, 'consultarApiPorDocumento'])->name('admin.operaciones.consultar.api')->middleware(['auth', 'can:admin.operaciones.consultar']);
Route::get('/admin/operaciones/cargar', [OperacionController::class, 'cargar'])->name('admin.operaciones.cargar')->middleware(['auth', 'can:admin.operaciones.cargar']);
Route::post('/admin/operaciones/store', [OperacionController::class, 'store'])->name('admin.operaciones.store')->middleware(['auth', 'can:admin.operaciones.store']);
Route::get('/admin/operaciones/show', [OperacionController::class, 'show'])->name('admin.operaciones.show')->middleware(['auth', 'can:admin.operaciones.show']);

//RUTA PARA BUSCAR LAS LOCALIDADES SEGÚN LA PROVINCIA Y LA LOCALIDAD
Route::get('/admin/localidades/{idProv}', [LocalidadController::class, 'getLocalidades']);
Route::get('/admin/codpostales/{idLocal}', [LocalidadController::class, 'getCodigosPostales']);
