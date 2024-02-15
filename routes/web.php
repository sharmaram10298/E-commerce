<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\VendorProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\User\WishlistController;

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
    return view('frontend.index');
});
Route::get('/',[IndexController::class,'Index']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'UserDashboard'])->name('dashbosrd');
    Route::get('/User/logout', [UserController::class, 'Userlogout'])->name('user.logout');



    Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::post('user/change/password', [UserController::class, 'UserChangePassword'])->name('user.change.password');
});
// Route::get('/index', function () {
//     return view('index');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashbosrd');
    Route::get('/admin/logout', [AdminController::class, 'Adminlogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');



    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('update.password');

});

Route::middleware(['auth','role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashbosrd');
    Route::get('/vendor/logout', [VendorController::class, 'Vendorlogout'])->name('vendor.logout');
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfile'])->name('vendor.profile');
    Route::get('/vendor/change/password', [VendorController::class, 'VendorChangePassword'])->name('vendor.change.password');

    Route::post('/vendor/profile/store', [VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');
    Route::post('/vendor/update/password', [VendorController::class, 'VendorUpdatePassword'])->name('vendor.update.password');


    Route::controller(VendorProductController::class)->group(function(){
        Route::get('/vendor/all/product','VendorAllProduct')->name('vendor.all.product');
        Route::get('/vendor/add/product','VendorAddProduct')->name('vendor.add.product');
        Route::get('/vendor/edit/product/{id}','VendorEditProduct')->name('vendor.edit.product');

        // Delete Multiple Images
        Route::get('/vendor/product/multiimg/delete/{id}' , 'VednorMulitImageDelelte')->name('vendor.product.multiimg.delete');

        
        Route::get('/vendor/product/delete/{id}' , 'VendorProductDelelte')->name('vendor.delete.product');



        
        Route::post('/vendor/store/product','VendorStoreProduct')->name('vendor.store.product');
        Route::post('/vendor/update/product/thambnail','VendorUpdateProdeuctThambnail')->name('vendor.update.product.thambnail');
        Route::post('/vendor/update/product/multiimg','VendorUpdateProductMultiimage')->name('vendor.update.product.multiimg');
        Route::post('/vendor/update/product','VendorUpdateProduct')->name('vendor.update.product');


        Route::get('/vendor/subcategory/ajax/{category_id}' , 'VendorGetSubCategory');

        Route::get('/vendor/product/inactive/{id}' , 'VendorProductInactive')->name('vendor.product.inactive');
        Route::get('/vendor/product/active/{id}' , 'VendorProductActive')->name('vendor.product.active');
        
        
    });






});

Route::get('/admin/login',[AdminController::class, 'AdminLogin'])->name('admin.login')->middleware(RedirectIfAuthenticated::class);
Route::get('/vendor/login',[VendorController::class, 'VendorLogin'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);


Route::get('/become/vendor',[VendorController::class, 'BecomeVendor'])->name('become.vendor');
Route::post('/vendor/register',[VendorController::class, 'VendorRegister'])->name('vendor.register');


Route::middleware(['auth','role:admin'])->group(function () {
    
    Route::controller(BrandController::class)->group(function(){
        Route::get('all/brand','AllBrand')->name('all.brand');
        Route::get('add/brand','AddBrand')->name('add.brand');
        Route::get('edit/brand/{id}','EditBrand')->name('edit.brand');
        Route::get('delete/brand/{id}','DeleteBrand')->name('delete.brand');



        
        Route::post('store/brand','StoreBrand')->name('store.brand');
        Route::post('update/brand','UpdateBrand')->name('update.brand');
    });


    Route::controller(CategoryController::class)->group(function(){
        Route::get('all/category','AllCategory')->name('all.category');
        Route::get('add/category','AddCategory')->name('add.category');
        Route::get('edit/category/{id}','EditCategory')->name('edit.category');
        Route::get('delete/category/{id}','DeleteCategory')->name('delete.category');



        
        Route::post('store/category','StoreCategory')->name('store.category');
        Route::post('update/category','UpdateCategory')->name('update.category');
    });

    Route::controller(SubCategoryController::class)->group(function(){
        Route::get('all/subcategory','AllSubCategory')->name('all.subcategory');
        Route::get('add/subcategory','AddSubCategory')->name('add.subcategory');
        Route::get('edit/subcategory/{id}','EditSubCategory')->name('edit.subcategory');
        Route::get('delete/subcategory/{id}','DeleteSubCategory')->name('delete.subcategory');

        Route::get('/subcategory/ajax/{category_id}' , 'GetSubCategory');


        
        Route::post('store/subcategory','StoreSubCategory')->name('store.subcategory');
        Route::post('update/subcategory','UpdateSubCategory')->name('update.subcategory');
    });
    //  Vendor Active and Inactive All Routes
    Route::controller(AdminController::class)->group(function(){
        Route::get('inactive/vendor','InactiveVendor')->name('inactive.vendor');
        Route::get('active/vendor','ActiveVendor')->name('active.vendor');
        Route::get('inactive/vendor/details/{id}','InactiveVendorDetails')->name('inactive.vendor.details');
        Route::get('active/vendor/details/{id}','ActiveVendorDetails')->name('active.vendor.details');


        Route::post('active/vendor/approve','ActiveVendorApprove')->name('active.vendor.approve');
        Route::post('inactive/vendor/approve','InactiveVendorApprove')->name('inactive.vendor.approve');
        
    });


    // Product  All Routes
    Route::controller(ProductController::class)->group(function(){
        Route::get('all/product','AllProduct')->name('all.product');
        Route::get('add/product','AddProduct')->name('add.product');
        Route::get('/edit/product/{id}' , 'EditProduct')->name('edit.product');
        Route::get('/product/multiimg/delete/{id}' , 'MulitImageDelelte')->name('product.multiimg.delete');

        // Delete Multiple Images
        Route::get('/product/delete/{id}' , 'ProductDelelte')->name('delete.product');



        
        Route::post('store/product','StoreProduct')->name('store.product');
        Route::post('update/product','UpdateProdeuct')->name('update.product');
        Route::post('update/product/thambnail','UpdateProdeuctThambnail')->name('update.product.thambnail');
        Route::post('/update/product/multiimage' , 'UpdateProductMultiimage')->name('update.product.multiimage');

        Route::get('/product/inactive/{id}' , 'ProductInactive')->name('product.inactive');
        Route::get('/product/active/{id}' , 'ProductActive')->name('product.active');
    });
 
    Route::controller(SliderController::class)->group(function(){
        Route::get('all/slider','AllSlider')->name('all.slider');
        Route::get('add/slider','AddSlider')->name('add.slider');
        Route::get('edit/slider/{id}','EditSlider')->name('edit.slider');
        Route::get('delete/slider/{id}','DeleteSlider')->name('delete.slider');


        Route::post('store/slider','StoreSlider')->name('store.slider');
        Route::post('update/slider','UpdateSlider')->name('update.slider');
        
    });
    Route::controller(BannerController::class)->group(function(){
        Route::get('all/banner','AllBanner')->name('all.banner');
        Route::get('add/banner','AddBanner')->name('add.banner');
        Route::get('edit/banner/{id}','EditBanner')->name('edit.banner');
        Route::get('delete/banner/{id}','DeleteBanner')->name('delete.banner');


        Route::post('store/banner','StoreBanner')->name('store.banner');
        Route::post('update/banner','UpdateBanner')->name('update.banner');
        
    });

});//end middleware



Route::get('/product/details/{id}/{slug}',[IndexController::class,'ProductDetails'])->name('product.details');
Route::get('/vendor/details/{id}',[IndexController::class,'VendorDetails'])->name('vendor.details');
Route::get('/vendor/all',[IndexController::class,'VendorAll'])->name('vendor.all');

Route::get('/product/category/{id}/{slug}',[IndexController::class,'CatWiseProduct']);
Route::get('/product/subcategory/{id}/{slug}',[IndexController::class,'SubCatWiseProduct']);

// Product View Modal With Ajax


Route::get('/product/view/modal/{id}', [IndexController::class, 'ProductViewAjax']);

// AddTocart  store data
Route::post('/cart/data/store/{id}', [CartController::class, 'AddToCart']);

//  get data miniCart 
Route::get('/product/mini/cart', [CartController::class, 'AddMiniCart']);

// Remove data mini cart
Route::get('/minicart/product/remove/{rowId}', [CartController::class, 'RemoveMiniCart']);

// product details addTocart product
Route::post('/dcart/data/store/{id}', [CartController::class, 'AddToCartDetails']);


// product details addTocart product
Route::post('/add-to-wishlist/{product_id}', [WishlistController::class, 'AddToWishlist']);



// User All Routes
Route::middleware(['auth', 'role:user'])->group(function(){

    Route::controller(WishlistController::class)->group(function(){
        Route::get('/wishlist', 'AllWishlist')->name('wishlist');
        Route::get('/get-wishlist-product' , 'GetWishlistProduct');
        Route::get('/wishlist-remove/{id}' , 'WishlistRemove');
    });

});

require __DIR__.'/auth.php';
