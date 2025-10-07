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
use App\Livewire\ItemsLowPage;
use App\Livewire\ItemsReturnPage;
use App\Livewire\ItemsSoldPage;
use App\Livewire\MyAccountEditPage;
use App\Livewire\MyAccountPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrdersUnpaidPage;
use App\Livewire\PartnersPage;
use App\Livewire\PaymentsPage;
use App\Livewire\PosCart;
use App\Livewire\PosPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProfitLossAllPage;
use App\Livewire\ProfitLossPage;
use App\Livewire\SuccessPage;
use App\Livewire\WalletPage;
use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CheckoutController;
use App\Livewire\MyPos;
use App\Livewire\PosSale;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

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
    Route::get('/items-sold', ItemsSoldPage::class);
    Route::get('/items-return', ItemsReturnPage::class);
    Route::get('/items-low', ItemsLowPage::class);
    Route::get('/dompet', WalletPage::class);
    Route::get('/laba-rugi', ProfitLossPage::class);
    Route::get('/laba-rugi-all', ProfitLossAllPage::class);
    Route::get('/neraca', BalanceSheetPage::class);
    Route::get('/neraca-all', BalanceSheetAllPage::class);
    Route::get('/payments', PaymentsPage::class);
    Route::get('/pos', PosPage::class);
    Route::get('/poscart', PosCart::class);

    Route::get('/api/products', [ProductController::class, 'index']);
    Route::get('/api/products/{id}', [ProductController::class, 'show']);

    Route::post('/api/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

    Route::get('/my-pos', function () {
        $lastCart = Cart::where('branch_id', Auth::user()->branch_id)->get();
        // siapkan array sederhana yang mudah di-JSON-kan
        $initialCart = [];
        if ($lastCart) {
            $initialCart = $lastCart->map(function($it) {
                    return [
                        'id' => $it->product_id,
                        'name' => $it->name ?? '',
                        'variant' => $it->variant ?? '',
                        'weight' => (float) $it->total_weight / $it->quantity ?? '',
                        'quantity' => (int) $it->quantity,
                        'price' => (float) $it->unit_amount,
                        'subtotal' => (float) $it->total_amount,
                        'subtotalweight' => (float) $it->total_weight,
                    ];
            })->toArray();
        }
        Cart::where('branch_id', Auth::user()->branch_id)->delete();
        return view('my-pos', compact('initialCart'));
    });

    Route::get('/mypos', MyPos::class);

    Route::get('/printprevieworder/{id}', [PrintController::class, 'printvieworder'])->name('printorder');
    Route::get('/printprevieworderprocess/{id}', [PrintController::class, 'printvieworderprocess'])->name('printorderprocess');
    Route::get('/printpreviewtransferstock/{id}', [PrintController::class, 'printviewtransferstock'])->name('printtransferstock');
    Route::get('/exportproducts', [ExportController::class, 'exportProduct']);
    Route::get('/exportbrands', [ExportController::class, 'exportBrand']);
    Route::get('/exportdompetbydatetransaksi/{byfilter}', [ExportController::class, 'exportDompetDateTrans'])->name('exportdompetbydatetransaksi');
    Route::get('/exportdompetbydatedibuat/{byfilter}', [ExportController::class, 'exportDompetDateDibuat'])->name('exportdompetbydatedibuat');
});
