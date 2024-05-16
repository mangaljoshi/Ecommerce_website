<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('admin/login',[AdminLoginController::class,'index'])->name('admin.login');
Route::post('admin/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
Route::match(['get', 'post'], 'admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
Route::match(['get', 'post'], '/admin/logout', [HomeController::class, 'logout'])->name('admin.logout');

// categories route
Route::get('admin/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');

Route::post('admin/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');

Route::get('/get-slug', [CategoryController::class, 'getSlug'])->name('getSlug');
Route::get('/search-categories', [CategoryController::class, 'searchCategories'])->name('searchCategories');


{
    $slug = "";
    if (!empty($request->title)) {
        $slug = Str::slug($request->title);
    }

    return response()->json([
        'status' => true,
        'slug' => $slug,
    ]);
}



