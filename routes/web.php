<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\JustificanteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DireccionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('index');
});

Route::get('/alumno', function(){
    return view('Alumno');
})->name('alumno');

Route::get('/tutor', function(){
    return view('Tutor');
})->name('tutor');

Route::get('/docente', function(){
    return view('Docente');
})->name('docente');

Route::get('/login', function(){
    return view('Login');
})->name('login');
*/
/* Formulario  ----***---
Route::get('/login', function(){ return view('Login'); })->name('login');
Route::post('/login-check', [LoginController::class, 'login'])->name('login.check');

// Vistas por Rol
Route::get('/alumno',  function(){ return view('Alumno');  })->name('alumno');
Route::get('/tutor',   function(){ return view('Tutor');   })->name('tutor');
Route::get('/docente', function(){ return view('Docente'); })->name('docente');

Route::get('/', function () { return view('index'); });

// routes/web.php
Route::get('/logout', function () {
    return redirect()->route('login');
})->name('logout');


Route::post('/justificantes/guardar', 'App\Http\Controllers\JustificanteController@store')->name('justificantes.store');

Route::get('/alumno', [JustificanteController::class, 'index'])->name('alumno.index');
Route::post('/enviar-justificante', [JustificanteController::class, 'store'])->name('justificantes.store');

// Ruta de Logout (Para que no de error tu botón de cerrar sesión)
Route::get('/logout', function () {
    return redirect('/');
})->name('logout');
*/

// Inicio y Login
Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('Login');
})->name('login');
Route::post('/login-check', [LoginController::class, 'login'])->name('login.check');

// Vistas por Rol
// NOTA: Cambiamos la ruta de alumno para que use el Controlador y cargue los datos de HeidiSQL
Route::get('/alumno', [JustificanteController::class, 'index'])->middleware('role:alumno')->name('alumno.index');

// Panel principal del tutor (con buscador)
Route::get('/tutor', [JustificanteController::class, 'indexTutor'])->middleware('role:tutor')->name('tutor.index');

// Acción de Aceptar o Rechazar
Route::post('/tutor/update/{id}', [JustificanteController::class, 'updateStatus'])->middleware('role:tutor')->name('tutor.update');

// Vista del panel docente
Route::get('/docente', [JustificanteController::class, 'indexDocente'])->middleware('role:docente')->name('docente.index');

// Acción de firmar (POST)
Route::post('/docente/firmar/{id}', [JustificanteController::class, 'firmarDocente'])->middleware('role:docente')->name('docente.firmar');

// Guardar Justificante
Route::post('/enviar-justificante', [JustificanteController::class, 'store'])->name('justificantes.store');

// Ruta para visualizar el PDF generado
Route::get('/justificante/pdf/{id}', [JustificanteController::class, 'verPDF'])->middleware('auth')->name('justificantes.pdf');

// Ruta pública para el escaneo del QR (Guardia)
Route::get('/validar/{id}', [JustificanteController::class, 'validarPublico'])->name('validar.publico');

// Cerrar Sesión
Route::get('/logout', function () {
    return redirect('/');
})->name('logout');

// Rutas para Administrador
Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin')->name('admin.index');
Route::post('/admin/create-user', [AdminController::class, 'createUser'])->middleware('role:admin')->name('admin.createUser');
Route::get('/admin/user/{id}/edit', [AdminController::class, 'editUser'])->middleware('role:admin')->name('admin.editUser');
Route::put('/admin/user/{id}', [AdminController::class, 'updateUser'])->middleware('role:admin')->name('admin.updateUser');
Route::delete('/admin/user/{id}', [AdminController::class, 'destroyUser'])->middleware('role:admin')->name('admin.destroyUser');
Route::get('/admin/generate-report', [AdminController::class, 'generateReport'])->middleware('role:admin')->name('admin.generateReport');

// Rutas para Dirección
Route::get('/direccion', [DireccionController::class, 'index'])->middleware('role:direccion')->name('direccion.index');
Route::get('/direccion/reports', [DireccionController::class, 'viewReports'])->middleware('role:direccion')->name('direccion.viewReports');
