<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

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
// Formulario
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


