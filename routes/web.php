<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');
Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::delete('/delete-item',[CartController::class,'deleteItem'])->name('front.deleteItem.cart');

Route::get('/register', [AuthController::class,'register'])->name('account.register');
Route::post('/process-register', [AuthController::class,'processRegister'])->name('account.processRegister');

Route::group(['prefix' => 'admin'],function (){
//    admin
    Route::group(['middlewere' => 'admin.guest'],function (){
        Route::get('/login',[AdminController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminController::class,'authenticate'])->name('admin.authenticate');

    });
          Route::group(['middlewere' => 'admin.auth'],function (){
              Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
              Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');
//-----------catagory--controller----->
              Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
              Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
              Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
              Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
              Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
              Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');

//              Sub category Route
              Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');
              Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
              Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');
              Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
              Route::put('/sub-categories/{subCategory}',[SubCategoryController::class,'update'])->name('sub-categories.update');
              Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class,'destroy'])->name('sub-categories.delete');

//              Brands Route
              Route::get('/brands',[BrandsController::class,'index'])->name('brands.index');
              Route::get('/brands/create',[BrandsController::class,'create'])->name('brands.create');
              Route::post('/brands',[BrandsController::class,'store'])->name('brands.store');
              Route::get('/brands{brand}/edit',[BrandsController::class,'edit'])->name('brands.edit');
              Route::put('/brands/{brand}',[BrandsController::class,'update'])->name('brands.update');
              Route::delete('/brands/{brand}',[BrandsController::class,'destroy'])->name('brands.delete');

//              Product Route
              Route::get('/products',[ProductController::class,'index'])->name('products.index');
              Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
              Route::post('/products',[ProductController::class,'store'])->name('products.store');
              Route::get('/products{product}/edit',[ProductController::class,'edit'])->name('products.edit');
              Route::put('/products/{product}',[ProductController::class,'update'])->name('products.update');
              Route::delete('/products/{product}',[ProductController::class,'destroy'])->name('products.delete');
              Route::get('/get-products',[ProductController::class,'getProducts'])->name('products.getProducts');

              //          product    Sub category Route
              Route::get('/product-subcategories',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');

//              temp-images.create
              Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');

//              Product Images update route
              Route::post('/product-image/update',[ProductImageController::class,'update'])->name('product-images.update');
              Route::delete('/products-images',[ProductImageController::class,'destroy'])->name('product-images.destroy');

              //----------------slug-------------->
              Route::get('/getSlug', function (Request $request) {
                  $slug = '';
                  if (!empty($request->title)) {
                      $slug = Str::slug($request->title);
                  }
                  return response()->json([
                      'status' => true,
                      'slug' => $slug
                  ]);
              })->name('getSlug');

          });
});
