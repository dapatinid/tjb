<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\PrintController;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\BalanceSheetAllPage;
use App\Livewire\BalanceSheetPage;
use App\Livewire\BranchesPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\HomePage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\DonateDetailPage;
use App\Livewire\DonatesPage;
use App\Livewire\EventDetailPage;
use App\Livewire\EventsPage;
use App\Livewire\ItemsPage;
use App\Livewire\MyAccountEditPage;
use App\Livewire\MyAccountPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrdersUnpaidPage;
use App\Livewire\PartnersPage;
use App\Livewire\PaymentsPage;
use App\Livewire\PosPage;
use App\Livewire\PostDetailPage;
use App\Livewire\PostsPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProfitLossAllPage;
use App\Livewire\ProfitLossPage;
use App\Livewire\SuccessPage;
use App\Livewire\WalletPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
// Route::get('/@{slug}', PartnersPage::class);
// Route::get('/branches', BranchesPage::class);
// Route::get('/categories', CategoriesPage::class);
// Route::get('/products', ProductsPage::class);
// Route::get('/products/{slug}', ProductDetailPage::class);
Route::get('/cart', CartPage::class);

Route::get('/checkout', CheckoutPage::class);
Route::get('/my-orders', MyOrdersPage::class);
Route::get('/my-orders/{order}', MyOrderDetailPage::class);

Route::get('/login', LoginPage::class);
// Route::get('/register', RegisterPage::class);
Route::get('/forgot', ForgotPasswordPage::class);
Route::get('/reset', ResetPasswordPage::class);

Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
    // Route::get('/register', RegisterPage::class)->name('register');
    Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {

    Route::get('/register', RegisterPage::class)->name('register');

    Route::get('/@{slug}', PartnersPage::class);
    Route::get('/branches', BranchesPage::class);
    Route::get('/categories', CategoriesPage::class);
    Route::get('/products', ProductsPage::class);
    Route::get('/products/{slug}', ProductDetailPage::class);

    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/');
    });
    Route::get('/checkout', CheckoutPage::class);
    Route::get('/my-orders-unpaid', MyOrdersUnpaidPage::class);
    Route::get('/my-orders', MyOrdersPage::class);
    Route::get('/my-orders/{id}', MyOrderDetailPage::class);
    Route::get('/success', SuccessPage::class)->name('success');
    Route::get('/cancel', CancelPage::class);
    Route::get('/my-account', MyAccountPage::class);
    Route::get('/my-account-edit', MyAccountEditPage::class);
    Route::get('/items', ItemsPage::class);
    Route::get('/dompet', WalletPage::class);
    Route::get('/laba-rugi', ProfitLossPage::class);
    Route::get('/laba-rugi-all', ProfitLossAllPage::class);
    Route::get('/neraca', BalanceSheetPage::class);
    Route::get('/neraca-all', BalanceSheetAllPage::class);
    Route::get('/payments', PaymentsPage::class);
    Route::get('/pos', PosPage::class);

    Route::get('/printprevieworder/{id}', [PrintController::class, 'printvieworder'])->name('printorder');
    Route::get('/printprevieworderprocess/{id}', [PrintController::class, 'printvieworderprocess'])->name('printorderprocess');
    Route::get('/printpreviewtransferstock/{id}', [PrintController::class, 'printviewtransferstock'])->name('printtransferstock');
    Route::get('/exportproducts', [ExportController::class, 'exportProduct']);
    Route::get('/exportbrands', [ExportController::class, 'exportBrand']);
});
