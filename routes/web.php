<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendingCatalogs;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::match(['get', 'post'], '/', [AuthController::class, 'index']);
Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
Route::get('/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
Route::get('/reset/password/{token}', [AuthController::class, 'resetPassword']);
Route::post('/reset/password', [AuthController::class, 'submitResetPasswordForm'])->name('submit.reset.password');   

//Authenticated Group Routes Starts
Route::group(['middleware' => ['auth']], function() {  

    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
        
        //Protected Routes For Admin
           Route::group(['middleware' => ['admin']], function() {
            //Users Routes
            Route::get('/users', [UsersController::class,'index'])->name('users.index');
            Route::post('/users/add/',[UsersController::class,'store']);
            Route::get('/users/edit/{id}',[UsersController::class,'edit'])->name('users.edit');
            Route::post('/users/{user}',[UsersController::class,'update'])->name('users.update');
            Route::delete('/users/delete/{user}',[UsersController::class,'destroy'])->name('users.destroy');
        });
        //Ends Protected Routes For Admin

        Route::get('/catalogs', [CatalogController::class,'index'])->name('catalogs.index');
        Route::post('/catalogs/add/',[CatalogController::class,'store']);
        Route::get('/catalogs/edit/{id}',[CatalogController::class,'edit'])->name('catalogs.edit');
        Route::post('/catalogs/{catalog}',[CatalogController::class,'update'])->name('catalogs.update');
        Route::delete('/catalogs/delete/{catalog}',[CatalogController::class,'destroy'])->name('catalogs.destroy');
        Route::get('/catalog/{id}',[CatalogController::class,'show'])->name('catalogs.show');
        Route::get('/pending-catalogs', [PendingCatalogs::class,'index'])->name('pending-catalogs.index');
        Route::post('/pending-catalogs/add/',[PendingCatalogs::class,'store']);
        Route::get('/pending-catalogs/edit/{id}',[PendingCatalogs::class,'edit'])->name('pending-catalogs.edit');
        Route::post('/pending-catalogs/{catalog}',[PendingCatalogs::class,'update'])->name('pending-catalogs.update');
        Route::delete('/pending-catalogs/delete/{catalog}',[PendingCatalogs::class,'destroy'])->name('pending-catalogs.destroy');
        Route::post('/pending-catalogs-publish',[PendingCatalogs::class,'publishPendingCatalog'])->name('pending-catalogs.publish');

        Route::get('/fetch-catalog-categories', [CatalogController::class,'fetchCategories']);
        
        // Export csv file
         Route::get('/export', [CatalogController::class,'exportCSV'])->name('export.index');
     
        //Import csv file
        Route::post('importCSV', [CatalogController::class, 'importCSV']);
        // Download  CSV Format
        Route::get('/download_csv', [CatalogController::class,'download_csv']);

    Route::get('logout', [AuthController::class, 'logOut'])->name('logout');
    // User count api
    Route::post('get_user_data/{id}', [CatalogController::class, 'get_user_data']);
    Route::get('catalogs-sync', [CatalogController::class, 'catalogs_sync'])->name('catalogs-sync.index');
    
    Route::post('show_pro_his', [CatalogController::class, 'show_pro_his']);
    Route::get('testing_api', [CatalogController::class, 'testing_api']);
    Route::get('getCatlogs', [CatalogController::class, 'getCatlogs']);

    Route::get('catalogData', [CatalogController::class, 'catalogData']);
    Route::post('assignCatalog', [CatalogController::class, 'assignCatalog']);
    Route::post('addCatalog', [CatalogController::class, 'addCatalog']);
    Route::get('not-assigned', [CatalogController::class, 'pro_not_assigned']);
    Route::post('sbtnotAssigned', [CatalogController::class, 'sbtnotAssigned']);
});
//Authenticated Group Routes Ends






