<?php

use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('admin/login',[AdminLoginController::class,'index'])->name('admin.login');
Route::post('admin/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
Route::match(['get', 'post'], 'admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
Route::match(['get', 'post'], '/admin/logout', [HomeController::class, 'logout'])->name('admin.logout');

// categories route
Route::get('admin/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::get('/categories/create', [CategoryController::class,'create'])->name('admin.categories.create');
Route::post('/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
Route::get('/search-categories', [CategoryController::class, 'searchCategories'])->name('searchCategories');
Route::get('/categories/{category}', [CategoryController::class,'edit'])->name('admin.categories.edit');
Route::put('/categories/{category}/', [CategoryController::class, 'update'])->name('admin.categories.update');
Route::delete('/categories/{category}/', [CategoryController::class, 'destroy'])->name('admin.categories.delete');

// Sub Category Route
Route::get('admin/sub_categories', [SubCategoryController::class, 'index'])->name('sub_categories.index');
Route::get('admin/sub_categories/create', [SubCategoryController::class,'create'])->name('admin.sub_categories.create');
Route::post('admin/sub_categories/store', [SubCategoryController::class, 'store'])->name('admin.sub_categories.store');
Route::get('sub_categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub_categories.edit');
Route::put('sub_categories/{subCategory}/update', [SubCategoryController::class, 'update'])->name('sub_categories.update');
Route::delete('/sub_categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub_categories.delete');


// Brands Route 
Route::get('admin/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('admin/brands/create' , [BrandController::class,'create'])->name('admin.brands.create');
Route::post('admin/brands/store', [BrandController::class, 'store'])->name('admin.brands.store');
Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
Route::put('brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');


// Product Controller

Route::get('products/create' , [ProductController::class,'create'])->name('admin.products.create');








//temp-images.create
Route::post('temp_images/create', [TempImagesmageController:: class,'create'])->name('temp_images.create');

// Route::get('/get-slug', [CategoryController::class, 'getSlug'])->name('getSlug');

Route::get('/getSlug', function(Request $request){
    $slug = "";
    if (!empty($request->title)) {
        $slug = Str::slug($request->title);
    }

    return response()->json([
        'status' => true,
        'slug' => $slug,
    ]);
})->name('getSlug');



