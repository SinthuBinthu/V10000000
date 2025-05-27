<?php


use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthAdmin;
use Intervention\Image\Facades\Image;   
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PageController;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class,'index'])->name('shop.index');
Route::get('/shop/{product_slug}',[ShopController::class,'product_details'])->name("shop.product.details");

Route::get('/admin/coupons',[AdminController::class,'coupons'])->name('admin.coupons');
Route::get('/admin/coupon/add',[AdminController::class,'add_coupon'])->name('admin.coupon.add');
Route::post('/admin/coupon/store',[AdminController::class,'store_coupon'])->name('admin.coupon.store');
Route::get('/admin/coupon/edit/{id}',[AdminController::class,'edit_coupon'])->name('admin.coupon.edit');
Route::put('/admin/coupon/update',[AdminController::class,'update_coupon'])->name('admin.coupon.update');
Route::delete('/admin/coupon/{id}/delete',[AdminController::class,'delete_coupon'])->name('admin.coupon.delete');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');

Route::post('/cart/store', [CartController::class, 'addtocart'])->name('cart.add');

Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');
Route::post('/place-order',[CartController::class,'place_order'])->name('cart.place.order');
Route::get('/order-confirmation',[CartController::class,'confirmation'])->name('cart.confirmation');

Route::get('/admin/orders',[AdminController::class,'orders'])->name('admin.orders');
Route::get('/admin/order/items/{order_id}',[AdminController::class,'order_items'])->name('admin.order.items');

Route::get('/account-orders',[UserController::class,'account_orders'])->name('user.account.orders');

Route::get(    '/account/orders/{order}',    [UserController::class,'account_order_details']  )->name('user.account.order.details');

Route::put('/cart/increase-qunatity/{rowId}',[CartController::class,'increase_cart_quantity'])->name('cart.increase.qty');
Route::put('/cart/reduce-qunatity/{rowId}',[CartController::class,'reduce_cart_quantity'])->name('cart.reduce.qty');
Route::delete('/cart/remove/{rowId}',[CartController::class,'remove_item_from_cart'])->name('cart.remove');
Route::delete('/cart/clear',[CartController::class,'empty_cart'])->name('cart.empty');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard',[UserController::class,'index'])->name('user.index');

    Route::post('/wishlist/add', [WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
    
});

Route::middleware([AuthAdmin::class])->group(function(){
    Route::get('/admin',[AdminController::class,'index'])->name('admin.index');
    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brand/add',action: [AdminController::class,'add_brand'])->name('admin.brand.add');
    Route::post('/admin/brand/store',action: [AdminController::class,'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}',[AdminController::class,'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brand/update',[AdminController::class,'brand_update'])->name('admin.brand.update');
    Route ::delete('/admin/brand{id}/delete',[AdminController::class,'brand_delete'])->name('admin.brand.delete');

    Route::get('/admin/categories',[AdminController::class,'categories'])->name('admin.categories');

    Route::get('/admin/category/add',[AdminController::class,'add_category'])->name('admin.category.add');
    
    Route::post('/admin/category/store',[AdminController::class,'add_category_store'])->name('admin.category.store');

    Route::get('/admin/category/{id}/edit',[AdminController::class,'edit_category'])->name('admin.category.edit');

    Route::put('/admin/category/update',[AdminController::class,'update_category'])->name('admin.category.update');
    
    Route::delete('/admin/category/{id}/delete',[AdminController::class,'delete_category'])->name('admin.category.delete');

    Route::get('/admin/products',[AdminController::class,'products'])->name('admin.products');

    route::get('/admin/product/add',[AdminController::class,'product_add'])->name('admin.product.add');

    route::post('/admin/product/store',[AdminController::class,'product_store'])->name('admin.product.store');

    route::get('/admin/product/{id}/edit',[AdminController::class,'product_edit'])->name('admin.product.edit');

    Route::put('/admin/product/update',[AdminController::class,'update_product'])->name('admin.product.update');

    Route::delete('/admin/product/{id}/delete',[AdminController::class,'delete_product'])->name('admin.product.delete');

    
    Route::put('/admin/order/update-status', [AdminController::class, 'update_order_status'])->name('admin.order.status.update');

    Route::put(        '/account-order/cancel-order',        [UserController::class, 'order_cancel']    )->name('user.order.cancel');

    Route::get('contact-us',[HomeController::class,'contact'])->name('home.contact');
    Route::post('contact-us/store',[HomeController::class,'contact_store'])->name('home.contact.store');
    Route::get('/admin/contact',[AdminController::class,'contacts'])->name('admin.contacts');
    Route::delete('/admin/contact/{id}/delete', [AdminController::class, 'contact_delete'])->name('admin.contact.delete');
    Route::post('/admin/contact/{id}/reply', [AdminController::class, 'sendQuickReply'])->name('admin.contact.reply');

    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    route::get('/admin/slide/add', [AdminController::class, 'slide_add'])->name('admin.slide.add');
    route::post('/admin/slide/store', [AdminController::class, 'slide_store'])->name('admin.slide.store');
    route::get('/admin/slide/{id}/edit', [AdminController::class, 'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update', [AdminController::class, 'slide_update'])->name('admin.slide.update');
    Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'slide_delete'])->name('admin.slide.delete');

    Route::get('/about', [PageController::class, 'about'])->name('about.index');

});