<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Login & Register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Logout)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Dashboard Area (Protected by Role Middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')
    ->middleware('role:staff,admin,owner')
    ->group(function () {

    Route::view('/', 'index')->name('dashboard');
    Route::view('/home', 'index')->name('dashboard.home');

    /*
    |--------------------------------------------------------------------------
    | Inventory & Categories
    |--------------------------------------------------------------------------
    | Staff, Admin, dan Owner memiliki hak akses membaca logistik inventory
    */
    Route::controller(ProductController::class)->group(function () {
        Route::get('/inventory', 'index')->name('inventory');
        Route::get('/categories/search', 'searchCategory')->name('categories.search');
        Route::post('/categories/store', 'storeCategory')->name('categories.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Products CRUD Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('products')
        ->name('products.')
        ->controller(ProductController::class)
        ->group(function () {

            // Staff hanya diperbolehkan mengintip detail barang
            Route::get('/show/{product}', 'show')
                ->middleware('role:staff,admin,owner')
                ->name('show');

            // Admin & Owner memegang kontrol mutlak manipulasi data produk
            Route::get('/create', 'create')->middleware('role:admin,owner')->name('create');
            Route::post('/', 'store')->middleware('role:admin,owner')->name('store');
            Route::get('/edit/{product}', 'edit')->middleware('role:admin,owner')->name('edit');
            Route::put('/{product}', 'update')->middleware('role:admin,owner')->name('update');
            Route::delete('/{product}', 'destroy')->middleware('role:admin,owner')->name('destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | Purchase & Returns
    |--------------------------------------------------------------------------
    */
    Route::view('/purchase', 'purchase')->middleware('role:admin,owner')->name('purchase');
    Route::view('/sales-return', 'sales-return')->middleware('role:admin,owner')->name('sales-return');
    Route::view('/purchase-return', 'purchase-return')->middleware('role:admin,owner')->name('purchase-return');

    /*
    |--------------------------------------------------------------------------
    | Transactions Area
    |--------------------------------------------------------------------------
    */
    Route::prefix('transactions')
        ->name('transactions.')
        ->group(function () {  

            /* --- UTAMA: TABEL LAPORAN MUNCUL DI SINI --- */
            // Sekarang mengakses `dashboard/transactions/` langsung melempar tabel penjualan Anda
            Route::get('/', [TransactionController::class, 'salesReport'])
                ->middleware('role:admin,owner')
                ->name('sales');

            // Tetap menyediakan endpoint fallback /sales jika dibutuhkan oleh sistem navigasi menu Anda
            Route::get('/sales', [TransactionController::class, 'salesReport'])
                ->middleware('role:admin,owner');

            // Endpoint AJAX Modal Rincian Belanja
            Route::get('/{transaction}/detail', [TransactionController::class, 'showDetail'])
                ->middleware('role:admin,owner')
                ->name('detail');

            /* --- POS ENGINE INTERFACE --- */
            // Route ini untuk kasir/staff saat akan menginput atau membuat struk belanja baru
            Route::get('/create', [TransactionController::class, 'create'])
                ->middleware('role:staff,admin,owner')
                ->name('create');

            // API Pendukung Engine Transaksi Kasir
            Route::get('/search-product', [TransactionController::class, 'searchProduct'])->name('search-product');
            Route::get('/product/{product}', [TransactionController::class, 'getProduct'])->name('product');
            Route::post('/items/add', [TransactionController::class, 'addTransactionItem'])->name('items.add');
            Route::get('/items', [TransactionController::class, 'getTransactionItem'])->name('items');
            Route::delete('/items/{index}', [TransactionController::class, 'removeTransactionItem'])->name('items.remove');
            Route::post('/store', [TransactionController::class, 'store'])->name('store');
        });
});