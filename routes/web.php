<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*Controllers*/
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;

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
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/', function () { return Inertia::render('Home'); })->name('home'); /*HOME*/
    Route::get('/profil', function () { return Inertia::render('Profile/Show'); })->name('profile.edit'); /*PROFILE*/

    /*SERVICES*/

    Route::get('/factures', [InvoiceController::class, 'index'])->name('index.invoices');/*INVOICES*/
    Route::post('/factures/store', [InvoiceController::class, 'store'])->name('store.invoices');/*INVOICES*/
    Route::get('/factures/history', [InvoiceController::class, 'history'])->name('history.invoices');/*INVOICES*/
});

Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('tablero')->group(function () {

    Route::get('/conducteurs', function () {
        $users = User::where('active',true)->role('Driver')->with('profile')->paginate(10);
        return Inertia::render('Profile/Drivers', compact('users')); })->name('conducteurs'); /*DRIVERS*/

    Route::get('/conducteurs/nouveau', [AdminController::class, 'create_driver'])->name('create.driver');/*CREATE DRIVERS*/
    Route::post('/conducteurs/store', [AdminController::class, 'store_driver'])->name('store.driver');/*STORE DRIVERS*/
    Route::get('/conducteurs/edit/{id}', [AdminController::class, 'edit_driver'])->name('edit.driver');/*STORE DRIVERS*/
    Route::put('/conducteurs/edit/{id}', [AdminController::class, 'update_driver'])->name('update.driver');/*STORE DRIVERS*/
    Route::delete('/conducteurs/delete', [AdminController::class, 'delete_driver'])->name('delete.driver');/*DELETE DRIVERS*/
});
