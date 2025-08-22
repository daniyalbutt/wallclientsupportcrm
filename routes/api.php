<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiHomeController;
use App\Http\Controllers\BrandLeadsController;
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

Route::post('/leads', [ApiHomeController::class, 'leadStore']);
Route::post('/payments', [ApiHomeController::class, 'paymentStore']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Brand Lead API
Route::post('/brand-leads',[BrandLeadsController::class, 'store'])->name('brand_leads.store');
Route::post('/affiliate-leads',[BrandLeadsController::class, 'storeAffiliateLeads'])->name('affiliate_leads.store');

// Invoice Data API
Route::get('/invoice-bank', [ApiHomeController::class, 'getInvoices'])->name('invoice.bank');

// Generate CSRF
Route::get('/get-csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});