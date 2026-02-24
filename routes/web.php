<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\JustificanteController;
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
Route::get('/', function () { return view('index'); });

Route::get('/login', function(){ return view('Login'); })->name('login');
Route::post('/login-check', [LoginController::class, 'login'])->name('login.check');

// Vistas por Rol
// NOTA: Cambiamos la ruta de alumno para que use el Controlador y cargue los datos de HeidiSQL
Route::get('/alumno', [JustificanteController::class, 'index'])->name('alumno.index');

// Panel principal del tutor (con buscador)
Route::get('/tutor', [JustificanteController::class, 'indexTutor'])->name('tutor.index');

// Acción de Aceptar o Rechazar
Route::post('/tutor/update/{id}', [JustificanteController::class, 'updateStatus'])->name('tutor.update');

// Vista del panel docente
Route::get('/docente', [JustificanteController::class, 'indexDocente'])->name('docente.index');

// Acción de firmar (POST)
Route::post('/docente/firmar/{id}', [JustificanteController::class, 'firmarDocente'])->name('docente.firmar');

// Guardar Justificante
Route::post('/enviar-justificante', [JustificanteController::class, 'store'])->name('justificantes.store');

// Ruta para visualizar el PDF generado
Route::get('/justificante/pdf/{id}', [JustificanteController::class, 'verPDF'])->name('justificantes.pdf');

// Ruta pública para el escaneo del QR (Guardia)
Route::get('/validar/{id}', [JustificanteController::class, 'validarPublico'])->name('validar.publico');

// Cerrar Sesión
Route::get('/logout', function () {
    return redirect('/');
})->name('logout');