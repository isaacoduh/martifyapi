<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PosController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});



Route::resource('employees', EmployeeController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('products', ProductController::class);

Route::post('/stock/update/{id}', [ProductController::class, 'StockUpdate']);

Route::post('/getting/product/id', [PosController::class, 'getproduct']);

// cart routes
Route::get('/add-to-cart/{id}', [CartController::class, 'addtocart']);
Route::get('/cart/product', [CartController::class, 'cartproduct']);

Route::get('/remove/cart/{id}', [CartController::class, 'removecart']);

Route::get('/increment/{id}', [CartController::class, 'increment']);
Route::get('/decrement/{id}', [CartController::class, 'decrement']);

Route::get('/vats', [CartController::class, 'vats']);

Route::get('/orderdone', [PosController::class, 'completeorder']);

// Orders
Route::get('/orders', [OrderController::class, 'todayorders']);
Route::get('/order/details/{id}', [OrderController::class, 'orderdetails']);
Route::get('/order/orderdetails/{id}', [OrderController::class, 'orderdetailsall']);


Route::post('/search/order', [PosController::class, 'searchorderdate']);

Route::get('/today/sale', [PosController::class, 'todaysale']);
Route::get('/today/income', [PosController::class, 'todayincome']);
Route::get('/today/due', [PosController::class, 'todaydue']);
Route::get('/today/stockout', [PosController::class, 'stockout']);
