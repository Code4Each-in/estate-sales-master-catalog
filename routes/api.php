<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\PendingCatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('custom.token')->group(function () {
    Route::get('get-catalog-detail',[CatalogController::class,'getCatalogDetail']);
    Route::get('fetch-detail-catalogs-by-id',[CatalogController::class,'fetchCatalogsDetailWithStatusByIds']);
    Route::post('save-pending-catalog',[PendingCatalogController::class,'savePendingCatalog']);  
    Route::post('savecatalog', [CatalogController::class, 'savecatalog']);
});

