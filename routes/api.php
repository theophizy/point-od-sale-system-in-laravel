<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SaleController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
route::prefix('/Admin')->group( function(){
    //route::middleware('auth:admin')->group(function(){
route::get('/products/{barcode}', [ProductController::class, 'findByBarcode'])->name('product_scan');
  //  route::get('/products/{name}', [ProductController::class, 'searchByName']);
});
//});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
