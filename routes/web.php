<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CatagoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\TempImagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');
        
        // Catagory Routes
        Route::get('/catagories', [CatagoryController::class, 'index'])->name('catagories.index');
        Route::get('/catagories/create', [CatagoryController::class, 'create'])->name('catagories.create');
        Route::post('/catagories', [CatagoryController::class, 'store'])->name('catagories.store');
        Route::get('/catagories/{catagory}/edit', [CatagoryController::class, 'edit'])->name('catagories.edit');
        Route::put('/catagories/{catagory}', [CatagoryController::class, 'update'])->name('catagories.update');
        Route::delete('/catagories/{catagory}', [CatagoryController::class, 'destroy'])->name('catagories.delete');

        Route::post('/upload-tempImage', [TempImagesController::class, 'create'])->name('temp-images.create');

        Route::get('/getSlug', function (Request $request) {
            if (! empty($request->title)) {
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug,
            ]);
        })->name('getSlug');

    });
});
