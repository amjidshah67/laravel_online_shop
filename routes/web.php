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
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\OrderController;
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

// Route::get('/test', function () {
//    orderEmail(44);
// });

Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');
Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::delete('/delete-item',[CartController::class,'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout',[CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-checkout',[CartController::class,'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{orderId}',[CartController::class,'thankyou'])->name('front.thankyou');
Route::post('/get-order-summery',[CartController::class,'getOrderSummery'])->name('front.getOrderSummery');
Route::post('/apply-discount',[CartController::class,'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount',[CartController::class,'removeCoupon'])->name('front.removeCoupon');
Route::post('/add-to-wishlist',[FrontController::class,'addToWishlist'])->name('front.addToWishlist');



Route::group(['prefix' => 'account'],function (){
    Route::group(['middleware' => 'guest'],function () {

        Route::get('/login',[AuthController::class,'login'])->name('account.login');
        Route::post('/login',[AuthController::class,'authenticate'])->name('account.authenticate');

        Route::get('/register',[AuthController::class,'register'])->name('account.register');
        Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');


    });
    Route::group(['middleware' => 'auth'],function () {
        Route::get('/profile',[AuthController::class,'profile'])->name('account.profile');
        Route::get('/my-orders',[AuthController::class,'orders'])->name('account.orders');
        Route::get('/my-wishlist',[AuthController::class,'wishlist'])->name('account.wishlist');
        Route::post('/remove-Product-from-wishlist',[AuthController::class,'removeProductFromWishlist'])->name('account.removeProductFromWishlist');
        Route::get('/order-detail/{orderId}',[AuthController::class,'orderDetail'])->name('account.orderDetail');
        Route::get('/logout',[AuthController::class,'logout'])->name('account.logout');

    });
});


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

//              shipping route
              Route::get('/shipping/create',[ShippingController::class,'create'])->name('shipping.create');
              Route::post('/shipping',[ShippingController::class,'store'])->name('shipping.store');
              Route::get('/shipping/{id}',[ShippingController::class,'edit'])->name('shipping.edit');
              Route::put('/shipping/{id}',[ShippingController::class,'update'])->name('shipping.update');
              Route::delete('/shipping/{id}',[ShippingController::class,'destroy'])->name('shipping.destroy');

              //       Coupon Code route

              Route::get('/coupons',[DiscountCodeController::class,'index'])->name('coupons.index');
              Route::get('/coupons/create',[DiscountCodeController::class,'create'])->name('coupons.create');
              Route::post('/coupons',[DiscountCodeController::class,'store'])->name('coupons.store');
              Route::get('/coupons{coupon}/edit',[DiscountCodeController::class,'edit'])->name('coupons.edit');
              Route::put('/coupons/{coupon}',[DiscountCodeController::class,'update'])->name('coupons.update');
              Route::delete('/coupons/{coupon}',[DiscountCodeController::class,'destroy'])->name('coupons.delete');

              //  orders route

              Route::get('/orders',[OrderController::class,'index'])->name('orders.index');
              Route::get('/orders/{id}',[OrderController::class,'detail'])->name('orders.detail');
              Route::get('/order/change-status/{id}',[OrderController::class,'changeOrderStatus'])->name('orders.changeOrderStatus');
              Route::get('/order/send-email/{id}',[OrderController::class,'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');


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
