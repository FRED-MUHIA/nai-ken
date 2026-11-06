<?php

use App\Http\Controllers\Admin\ArtworkController as AdminArtworkController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MediaLibraryController as AdminMediaLibraryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageBuilderController as AdminPageBuilderController;
use App\Http\Controllers\Admin\ProductFormTemplateController as AdminProductFormTemplateController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\BusinessStationeryRequestController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicInvoiceController;
use App\Http\Controllers\PublicStorageController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/storage/{path}', [PublicStorageController::class, 'show'])->where('path', '.*')->name('storage.public');

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'sw'], true), 404);
    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::get('/', HomeController::class)->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/product-tags/{tag}', [ProductTagController::class, 'show'])->name('product-tags.show');

Route::get('/upload-artwork', [ArtworkController::class, 'create'])->name('artwork.create');
Route::post('/upload-artwork', [ArtworkController::class, 'store'])->name('artwork.store');
Route::delete('/upload-artwork/{artwork}', [ArtworkController::class, 'destroy'])->name('artwork.destroy');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{index}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{index}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/invoices/{order}', [PublicInvoiceController::class, 'show'])->middleware('signed')->name('invoices.show');

Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/blog', [PageController::class, 'blog'])->name('pages.blog');
Route::get('/blog/{post:slug}', [PageController::class, 'blogShow'])->name('pages.blog.show');
Route::get('/help', [PageController::class, 'help'])->name('pages.help');
Route::get('/design-services', [PageController::class, 'designServices'])->name('pages.design-services');
Route::get('/design-services/projects/{project}', [PageController::class, 'designProject'])->name('pages.design-projects.show');
Route::get('/custom-branded-receipts-invoices-delivery-books', [PageController::class, 'customReceipts'])->name('pages.custom-receipts');
Route::get('/branded-folders', [PageController::class, 'brandedFolders'])->name('pages.branded-folders');
Route::get('/certificates', [PageController::class, 'certificates'])->name('pages.certificates');
Route::get('/envelopes', [PageController::class, 'envelopes'])->name('pages.envelopes');
Route::get('/complementary-slips', [PageController::class, 'tokenBusinessPage'])->name('pages.token-business');
Route::redirect('/19hdEPSFQ4iUhtWoXHqg2E1kPCpUmaEgP8', '/complementary-slips', 301);
Route::post('/business-stationery-request', [BusinessStationeryRequestController::class, 'store'])->name('pages.business-stationery-request');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/orders/{order}/reorder', [CustomerDashboardController::class, 'reorder'])->name('dashboard.orders.reorder');
    Route::get('/dashboard/orders/{order}/invoice', [CustomerDashboardController::class, 'invoice'])->name('dashboard.orders.invoice');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'admin.reauth', 'admin.refresh_storefront'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::resource('categories', AdminCategoryController::class);
    Route::resource('product-form-templates', AdminProductFormTemplateController::class)
        ->except('show')
        ->parameters(['product-form-templates' => 'productFormTemplate']);
    Route::resource('products', AdminProductController::class);
    Route::resource('coupons', AdminCouponController::class);
    Route::resource('blog-posts', AdminBlogPostController::class)->except('show');
    Route::resource('page-builder', AdminPageBuilderController::class)
        ->except('show')
        ->parameters(['page-builder' => 'pageBuilder']);

    Route::get('/site-settings', [AdminSiteSettingController::class, 'edit'])->name('site-settings.edit');
    Route::put('/site-settings', [AdminSiteSettingController::class, 'update'])->name('site-settings.update');

    Route::get('/media-library', [AdminMediaLibraryController::class, 'index'])->name('media-library.index');
    Route::post('/media-library', [AdminMediaLibraryController::class, 'store'])->name('media-library.store');
    Route::delete('/media-library/{mediaLibrary}', [AdminMediaLibraryController::class, 'destroy'])->name('media-library.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/artworks', [AdminArtworkController::class, 'index'])->name('artworks.index');
    Route::get('/artworks/{artwork}/download', [AdminArtworkController::class, 'download'])->name('artworks.download');
    Route::get('/artworks/{artwork}', [AdminArtworkController::class, 'show'])->name('artworks.show');
    Route::patch('/artworks/{artwork}/status', [AdminArtworkController::class, 'updateStatus'])->name('artworks.status');
    Route::delete('/artworks/{artwork}', [AdminArtworkController::class, 'destroy'])->name('artworks.destroy');

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}', [AdminCustomerController::class, 'update'])->name('customers.update');
});

require __DIR__ . '/auth.php';
