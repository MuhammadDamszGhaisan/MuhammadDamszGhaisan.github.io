<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pelanggan\CartController;
use App\Http\Controllers\Pelanggan\CheckoutController;
use App\Http\Controllers\Pelanggan\HomeController;
use App\Http\Controllers\Pelanggan\NotificationController;
use App\Http\Controllers\Pelanggan\OrderController;
use App\Http\Controllers\Pelanggan\PaymentController;
use App\Http\Controllers\Pelanggan\ProductController;
use App\Http\Controllers\Pelanggan\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pelanggan.home');
    }

    return redirect()->route('login');
})->name('home');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('/login/pelanggan', [AuthController::class, 'loginPelanggan'])->name('login.pelanggan');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Area Pelanggan
Route::middleware(['auth', 'role:pelanggan'])->name('pelanggan.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
    Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pembayaran/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/pembayaran/{order}', [PaymentController::class, 'upload'])->name('payment.upload');

    Route::get('/pesanan/{order}', [OrderController::class, 'status'])->name('orders.status');
    Route::get('/riwayat', [OrderController::class, 'history'])->name('orders.history');

    Route::patch('/notifikasi/baca-semua', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::patch('/notifikasi/{id}/baca', [NotificationController::class, 'read'])->name('notifications.read');

    // Akun pelanggan: ubah data diri & ganti password sendiri
    Route::get('/akun', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/akun', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/akun/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Area Admin / Kasir
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/akun', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/akun/pin', [AdminProfileController::class, 'updatePin'])->name('profile.pin');

    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/pesanan/{order}/verify', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify');

    Route::resource('/produk', AdminProductController::class)
        ->parameters(['produk' => 'product'])
        ->except(['show'])
        ->names('products');

    Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/kategori/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/stok', [StockController::class, 'index'])->name('stocks.index');
    Route::patch('/stok/bulk', [StockController::class, 'bulk'])->name('stocks.bulk');
    Route::patch('/stok/{product}', [StockController::class, 'update'])->name('stocks.update');

    Route::get('/pelanggan', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/pelanggan/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transaksi/{order}/struk', [TransactionController::class, 'receipt'])->name('transactions.receipt');
    Route::get('/transaksi/{order}/whatsapp', [TransactionController::class, 'whatsapp'])->name('transactions.whatsapp');

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
});
