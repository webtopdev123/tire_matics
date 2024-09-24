<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\VueController;

Route::get('merchant/{merchantUrl}', [VueController::class, "merchant"]);
Route::get('contact-us', [VueController::class, "contactUs"]);
Route::get('about-us', [VueController::class, "aboutUs"]);
Route::get('ecatalog', [VueController::class, "ecatalog"]);
Route::get('productCategory', [VueController::class, "getCategory"]);
Route::get('productMaterial', [VueController::class, "getProductMaterial"]);
Route::get('productColor', [VueController::class, "getProductColor"]);
Route::get('productSearch', [VueController::class, "getProductSearch"]);

Route::get('productdetail', [VueController::class, "productDetail"]);
Route::get('product', [VueController::class, "product"]);