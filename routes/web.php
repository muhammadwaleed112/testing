<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\billGenerate;
use App\Http\Controllers\createBill;
use App\Http\Controllers\signupController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\profile;
use App\Http\Middleware\ValidUser;


// Middleware group
Route::middleware(ValidUser::class)->group(function(){
    Route::get('/', [ProductsController::class, 'dashboard']);
    Route::get('/profile', [profile::class, 'profile']);
    Route::post('/profile_update', [profile::class, 'profile_update']);
    Route::post('/updatePassword', [profile::class, 'updatePassword']);
    
            //dashsbaord
    Route::get('/inventroy', [ProductsController::class, 'index']);
    Route::post('/addProdcut', [ProductsController::class, 'insert']); //insert data
    Route::get('/getdata', [ProductsController::class, 'show']);//view detail
    Route::get('/edit', [ProductsController::class, 'edit']); //edit first step view 
    Route::post('/invupdate', [ProductsController::class, 'update']); //edit into update
    Route::post('/invdelete', [ProductsController::class, 'delete']); // softdelete
    
    // billGenerate
    Route::get('/bill', [billGenerate::class, 'index']);
    Route::post('/search', [billGenerate::class, 'search']);
    Route::post('/deleted', [billGenerate::class, 'delete']); // softdelete
    Route::get('/bill/{id}/details', [billGenerate::class, 'billDetails']); // softdelete
    // Route::get('/editnow/{id}/page', [billGenerate::class, 'editmy']); //edit first step view 
    Route::get('/editnow/{id}/page', [billGenerate::class, 'editmy'])->name('editmy');
    Route::post('/updatedata', [billGenerate::class, 'insert']); //edit into update
    
                    //createBill
    Route::get('/create', [createBill::class, 'index']);
    Route::post('/pos',[createBill::class,'insert'])->name('pos');
    Route::post('/calculate', [createBill::class, 'calculateTotal']);
    Route::post('insertdata', [createBill::class, 'insert'])->name('insertdata');
    // Route::get('/suplierview',[createBill::class,'show']);
    Route::get('/products/{id}/details', [createBill::class, 'getDetails']);
    Route::get('/product/{id}', [createBill::class, 'getProductName']);
    Route::get('/booking', [ProductsController::class, 'index']);

});

        //registration Form 
        Route::get('/login', [loginController::class, 'loginView']);
        Route::post('/loginview', [loginController::class, 'login']);
        Route::get('/logout', [loginController::class, 'logout']);