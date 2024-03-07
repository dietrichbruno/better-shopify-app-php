<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

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

Route::get('/api/auth', [AuthController::class, 'auth']);

Route::get('/api/auth/callback', [AuthController::class, 'authCallback']);

Route::get('/api/products/create', [ProductController::class, 'create'])->middleware('shopify.auth');

Route::get('/api/products/count', [ProductController::class, 'count'])->middleware('shopify.auth');

Route::get('/api/products', [ProductController::class, 'products'])->middleware('shopify.auth');

Route::get('/api/checkouts', [CheckoutController::class, 'checkouts'])->middleware('shopify.auth');

Route::post('/api/subscribe', [WebhookController::class, 'subscribe'])->middleware('shopify.auth');

Route::get('/notifications', [WebhookController::class, 'notifications']);

Route::get('/api/list-webhooks', [WebhookController::class, 'listWebhooks'])->middleware('shopify.auth');

Route::post('/api/webhooks', [WebhookController::class, 'webhooks']);

Route::fallback([AuthController::class, 'fallback'])->middleware('shopify.installed');

