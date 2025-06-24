<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\FilterController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\admin\CmsAdminController;

use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\Front\VendorController;
use App\Http\Controllers\Front\UserController;
use App\Http\Controllers\Front\AddressController;
use App\Http\Controllers\front\CmsController;
use App\Http\Controllers\front\EsewaController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\front\PaypalController;
use App\Http\Controllers\front\NewsletterController;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';


//LARAVEL ADMIN ROUTES
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    //ADMIN LOGIN ROUTES
    Route::match(['get', 'post'], 'login', [AdminController::class, 'login'])->name('login');
    Route::group(['middleware' => ['admin']], function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        //UPDATE ADMIN PASSWORD
        Route::match(['get', 'post'], 'update-admin-password', [AdminController::class, 'updateAdminPassword'])->name('update-admin-password');
        //UPDATE CURRENT PASSWORD

        Route::post('check-admin-password', [AdminController::class, 'checkAdminPassword'])->name('check-admin-password');

        //UPDATE ADMIN DETAILS
        Route::match(['get', 'post'], 'update-admin-details', [AdminController::class, 'updateAdminDetails'])->name('update-admin-details');

        //UPDATE VENDOR DETAILS
        Route::match(['get', 'post'], 'update-vendor-details/{slug}', [AdminController::class, 'updateVendorDetails'])->name('update-vendor-details');

         //UPDATE VENDOR COMMISSION
        Route::post('update-vendor-commission', [AdminController::class, 'updateVendorCommission'])->name('update-vendor-commission');

        //VIEW ADMIN / SUB-ADMIN / VENDORS
        Route::get('admins/{type?}', [AdminController::class, 'admins'])->name('admins.type');

        //VIEW VENDOR DETAILS
        Route::get('view-vendor-details/{id}', [AdminController::class, 'viewVendorDetails'])->name('view-vendor-details');

        //UPDATE ADMIN STATUS
        Route::post('update-admin-status', [AdminController::class, 'updateAdminStatus'])->name('update-admin-status');

        //LOGOUT ADMIN ROUTE
        Route::get('logout', [AdminController::class, 'logout'])->name('logout');


        // SECTION ADMIN ROUTE
        Route::get('sections', [SectionController::class, 'sections'])->name('sections');
        Route::post('update-section-status', [SectionController::class, 'updateSectionStatus'])->name('update-section-status');
        Route::get('delete-section/{id}', [SectionController::class, 'deleteSection'])->name('delete-section');
        Route::match(['get', 'post'], 'add-edit-section/{id?}', [SectionController::class, 'addEditSection'])->name('add-edit-section');

        //CATEGORY ADMIN ROUTE
        Route::get('categories', [CategoryController::class, 'categories'])->name('categories');
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus'])->name('update-category-status');
        Route::match(['get', 'post'], 'add-edit-category/{id?}', [CategoryController::class, 'addEditCategory'])->name('add-edit-category');
        Route::get('delete-category/{id}', [CategoryController::class, 'deleteCategory'])->name('delete-category');
        Route::get('append-categories-level', [CategoryController::class, 'appendCategoryLevel'])->name('append-categories-level');

        // BRANDS ADMIN ROUTE
        Route::get('brands', [BrandController::class, 'brands'])->name('brands');
        Route::post('update-brand-status', [BrandController::class, 'updateBrandStatus'])->name('update-brand-status');
        Route::get('delete-brand/{id}', [BrandController::class, 'deleteBrand'])->name('delete-brand');
        Route::match(['get', 'post'], 'add-edit-brand/{id?}', [BrandController::class, 'addEditBrand'])->name('add-edit-brand');

        // PRODUCTS ADMIN ROUTE
        Route::get('products', [ProductController::class, 'products'])->name('products');
        Route::post('update-product-status', [ProductController::class, 'updateProductStatus'])->name('update-product-status');
        Route::get('delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('delete-product');
        Route::match(['get', 'post'], 'add-edit-product/{id?}', [ProductController::class, 'addEditProduct'])->name('add-edit-product');
        //Delte product image || Delete product video
        Route::get('delete-product-image/{id}', [ProductController::class, 'deleteProductImage'])->name('delete-product-image');
        Route::get('delete-product-video/{id}', [ProductController::class, 'deleteProductVideo'])->name('delete-product-video');

        //ADD ATTRIBUTES
        Route::match(['get', 'post'], 'add-edit-attributes/{id?}', [ProductController::class, 'addAttributes'])->name('add-attributes');
        Route::post('update-attribute-status', [ProductController::class, 'updateAttributeStatus'])->name('update-attribute-status');
        Route::get('delete-attribute/{id}', [ProductController::class, 'deleteAttribute'])->name('delete-attribute');
        Route::match(['get', 'post'], 'edit-attribute/{id}', [ProductController::class, 'editAttribute'])->name('edit-attribute');


        //ADD IMAGES
        Route::match(['get', 'post'], 'add-images/{id?}', [ProductController::class, 'addImages'])->name('add-images');
        Route::post('update-image-status', [ProductController::class, 'updateImageStatus'])->name('update-image-status');
        Route::get('delete-image/{id}', [ProductController::class, 'deleteImage'])->name('delete-image');

        // BANNER ADMIN ROUTE
        Route::get('banners', [BannerController::class, 'banners'])->name('banners');
        Route::post('update-banner-status', [BannerController::class, 'updateBannerStatus'])->name('update-banner-status');
        Route::get('delete-banner/{id}', [BannerController::class, 'deleteBanner'])->name('delete-banner');
        Route::match(['get', 'post'], 'add-edit-banner/{id?}', [BannerController::class, 'addEditBanner'])->name('add-edit-banner');

        //FILTER ADMIN ROUTE
        Route::get('filters', [FilterController::class, 'filters'])->name('filters');
        Route::post('update-filter-status', [FilterController::class, 'updateFilterStatus'])->name('update-filter-status');
        Route::get('delete-filter/{id}', [FilterController::class, 'deleteFilter'])->name('delete-filter');
        Route::match(['get', 'post'], 'add-edit-filter/{id?}', [FilterController::class, 'addEditFilter'])->name('add-edit-filter');
        //FILTER VALUES ADMIN ROUTE
        Route::get('filters-values', [FilterController::class, 'filtersValues'])->name('filters-values');
        Route::post('update-filter-values-status', [FilterController::class, 'updateFilterValuesStatus'])->name('update-filter-values-status');
        Route::get('delete-filter-value/{id}', [FilterController::class, 'deleteFilterValue'])->name('delete-filter-value');
        Route::match(['get', 'post'], 'add-edit-filter-value/{id?}', [FilterController::class, 'addEditFilterValue'])->name('add-edit-filter-value');

        Route::post('category-filters', [FilterController::class, 'categoryFilters'])->name('category-filters')->name('category-filters');

        //COUPONS ADMIN ROUTE
        Route::get('coupons', [CouponsController::class, 'coupons'])->name('coupons');
        Route::post('update-coupon-status', [CouponsController::class, 'updateCouponStatus'])->name('update-coupon-status');
        Route::get('delete-coupon/{id}', [CouponsController::class, 'deleteCoupon'])->name('delete-coupon');
        Route::match(['get', 'post'], 'add-edit-coupon/{id?}', [CouponsController::class, 'addEditCoupon'])->name('add-edit-coupon');

        //Users ADMIN ROUTE
        Route::get('users', [UsersController::class, 'users'])->name('users');
        Route::post('update-users-status', [UsersController::class, 'updateUsersStatus'])->name('update-users-status');

        //Orders ADMIN ROUTE
        Route::get('orders', [OrdersController::class, 'orders'])->name('orders');
        Route::get('orders/{id}', [OrdersController::class, 'orderDetails'])->name('orders-details');
        Route::post('update-order-status', [OrdersController::class, 'updateOrderStatus'])->name('update-order-status');
        Route::post('update-order-item-status', [OrdersController::class, 'updateOrderItemStatus'])->name('update-order-item-status');

        //Orders Invoice
        Route::get('orders-invoice/{id}', [OrdersController::class, 'ordersInvoice'])->name('orders-invoice');
        Route::get('orders-invoice/pdf/{id}', [OrdersController::class, 'viewPDFInvoice'])->name('orders-pdf-invoice');

        //Shipping Charges
        Route::get('shipping-charges', [ShippingController::class, 'shippingCharges'])->name('shipping-charges');
        Route::post('update-shipping-charges-status', [ShippingController::class, 'updateShippingStatus'])->name('update-shipping-charges-status');
        Route::match(['get', 'post'], 'add-edit-shipping-charges/{id?}', [ShippingController::class, 'addEditShippingCharges'])->name('add-edit-shipping-charges');
        Route::get('delete-shipping/{id}', [ShippingController::class, 'deleteShippingCharges'])->name('delete-shipping');

        //News letter Subscriber Route
        Route::get('subscribers', [NewsletterSubscriberController::class, 'subscribers'])->name('subscribers');
        Route::post('update-subscribers-status', [NewsletterSubscriberController::class, 'updateSubscribersStatus'])->name('update-subscribers-status');
        Route::get('delete-subscriber/{id}', [NewsletterSubscriberController::class, 'deleteSubscriber'])->name('delete-subscriber');
        Route::get('export-subscribers', [NewsletterSubscriberController::class, 'exportSubscribers'])->name('export-subscribers');

        //Cms Pages Route
        Route::get('cms-pages', [CmsAdminController::class, 'cmsPages'])->name('cms-pages');
        Route::post('update-cms-pages-status', [CmsAdminController::class, 'updateCmsPagesStatus'])->name('update-cms-pages-status');
        Route::match(['get', 'post'], 'add-edit-cms-pages/{id?}', [CmsAdminController::class, 'addEditCmsPages'])->name('add-edit-cms-pages');
        Route::get('delete-cms-pages/{id}', [CmsAdminController::class, 'deleteCmsPages'])->name('delete-cms-pages');
    });
});

Route::get('orders-invoice/download/{id}', [App\Http\Controllers\Admin\OrdersController::class, 'viewPDFInvoice'])->name('orders-download-pdf-invoice');

//LARAVEL FRONT-END ROUTES
Route::namespace('App\Http\Controllers\Front')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');

    //LISTING / CATEGORIES ROUTES
    $catUrls = Category::select('url')->where('status', 1)->get()->pluck('url')->toArray();
    foreach ($catUrls as $key => $url) {
        Route::match(['get', 'post'], '/' . $url, [ProductsController::class, 'listing'])->name('listing');
    }

    //VENDOR PRODUCTS
    Route::get('/products/{vendorid}', [ProductsController::class, 'vendorListing'])->name('vendor-product-listing');

    //FOREND Product Detail Page
    Route::get('/product/{id}', [ProductsController::class, 'detail'])->name('product');

    //GET PRODUCT ATTRIBUTE PRICE
    Route::post('/get-product-price', [ProductsController::class, 'getProductPrice'])->name('get-product-price');

    //FOREND VENDOR LOGIN /REGISTER
    Route::get('vendor/login-register', [VendorController::class, 'loginRegister'])->name('vendor-login-register');
    //VENDOR REGISTER
    Route::post('vendor/register', [VendorController::class, 'vendorRegister'])->name('vendor-register');

    //Confirm Vendor Account
    Route::get('vendor/confirm/{code}', 'VendorController@confirmVendor')->name('vendor-confirm-code');

    //Add to Cart Route
    Route::post('add/cart', [ProductsController::class, 'addCart'])->name('add-cart');

    //Cart Route
    Route::get('/cart', [ProductsController::class, 'cart'])->name('cart');

    //Cart Update
    Route::post('/cart-update', [ProductsController::class, 'cartUpdate'])->name('cart-update');

    //Cart Delete
    Route::post('/cart-delete', [ProductsController::class, 'cartDelete'])->name('cart-delete');

    //User login-register
    // Route::get('user/login-register', ['as'=>'login','uses'=>UserController::class, 'loginRegister'])->name('user-login-register');
    Route::get('user/login-register', [UserController::class, 'loginRegister'])->name('login')->name('user-login-register');

    //User Register
    Route::post('user/register', [UserController::class, 'userRegister'])->name('user-register');

    //Search Products
    Route::get('/search-products', [ProductsController::class, 'listing'])->name('search-products');

    //
    Route::match(['get', 'post'], '/' . $url, [ProductsController::class, 'listing'])->name('listing');

    //front Cms Pages
    Route::match(['get', 'post'],'contact', [CmsController::class, 'contact'])->name('contact');    

    $cmsUrls = CmsPage::select('url')->where('status', 1)->get()->pluck('url')->toArray();
    // foreach ($cmsUrls as $key => $url) {
    //     dd($url);
    //     Route::get('cms-pages', [CmsController::class, 'cmsPages'])->name('cms-pages');
    // }
    foreach ($cmsUrls as $url) {
    // this will register GET /about-us, GET /faq, etc.
    Route::get($url, [CmsController::class,'cmsPages'])
         ->name("cms-{$url}");
    }

    //add Subscriber email
    Route::post('user-subscriber-email', [NewsletterController::class, 'addSubscriberEmail'])->name('user-subscriber-email');

    Route::group(['middleware' => ['auth']], function () {
        //User Account
        Route::match(['get', 'post'], 'user/account', [UserController::class, 'userAccount'])->name('user-account');
        //Update Password
        Route::match(['get', 'post'], 'user/update-password', [UserController::class, 'userUpdatePassword'])->name('user-update-password');
        Route::post('/apply-coupon', [ProductsController::class, 'applyCoupon'])->name('apply-coupon');

        //Route for checkout
        Route::match(['get', 'post'], 'checkout', [ProductsController::class, 'checkout'])->name('checkout');
        //User Login
        Route::post('get-delivery-address', [AddressController::class, 'getDeliveryAddress'])->name('get-delivery-address');
        Route::post('save-delivery-address', [AddressController::class, 'saveDeliveryAddress'])->name('save-delivery-address');
        Route::post('remove-delivery-address', [AddressController::class, 'removeDeliveryAddress'])->name('remove-delivery-address');

        Route::get('thanks', [ProductsController::class, 'thanks'])->name('user-thanks');
        Route::get('user-orders/{id?}', [OrderController::class, 'orders'])->name('user-orders');

        //Paypal
        Route::get('paypal', [PaypalController::class, 'paypal'])->name('paypal');
        Route::post('pay', [PaypalController::class, 'pay'])->name('payment');
        Route::get('success', [PaypalController::class, 'success'])->name('success');
        Route::get('error', [PaypalController::class, 'error'])->name('error');

        //Esewa
        Route::get('esewa', [EsewaController::class, 'esewa'])->name('esewa');
        Route::post('esewa-pay', [EsewaController::class, 'pay'])->name('esewa-payment');
        Route::get('esewa-success', [EsewaController::class, 'success'])->name('esewa-success');
        Route::get('esewa-error', [EsewaController::class, 'error'])->name('esewa-error');
    });

    //User Login
    Route::post('user/login', [UserController::class, 'userLogin'])->name('user-login');

    //User Logout
    Route::get('user/logout', [UserController::class, 'userLogout'])->name('user-logout');

    //Confirm User Account
    Route::get('user/confirm/{code}', [UserController::class, 'confirmUser'])->name('user-confirm-code');

    //User Forget Password
    Route::match(['get', 'post'], 'user/forgot-password', [UserController::class, 'forgotPassword'])->name('user-forgot-password');
});
