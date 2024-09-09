<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//for version 9+
Route::controller(ProductController::class)->group(function () {
    //For Others Microservices: Users-Shops
    Route::get('/products', 'index');
    Route::get('/product_affiliation', 'product_affiliation'); 
    Route::get('/stockproduct/{id}', 'stockproduct'); 
    Route::post('/update_stockproduct/{id}', 'update_stockproduct');

    //For CRUD
    Route::get('/list', 'list');
    Route::post('/products', 'store');
    Route::get('/products/{id}', 'show');
    Route::post('/products/{id}', 'update');
    Route::delete('/products/{id}', 'destroy');

    // For Dashboard - Admin
    Route::get('/product_name/{id}', 'product_name');

    //Test - Borrar
    Route::get('/crontest', 'crontest');    

});