<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfirmPasswordController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\productController;
use App\Http\Controllers\purchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\PurchaseReceivesController;
use App\Http\Controllers\ReturnedSalesController;
use App\Http\Controllers\SaleReceivesController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\WarehousesController;
use App\Models\warehouses;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class, "index"])->name('login');
Route::post('/', [AuthController::class, "signin"]);
Route::get('/clear-cache', function() {
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize');
    Artisan::call('cache:clear');
    return redirect()->back()->with('msg', 'Project Optimized');
});

Route::middleware('auth')->group(function (){
    Route::get('confirm-password', [ConfirmPasswordController::class, 'showConfirmPasswordForm'])->name('confirm-password');
    Route::post('confirm-password', [ConfirmPasswordController::class, 'confirmPassword']);

    Route::get('/logout', [AuthController::class, 'out']);

    Route::get('/dashboard', [dashboardController::class, "dashboard"]);
    Route::get('/addProduct', [productController::class, "add"]);

    Route::get('/category', [productController::class, "category"]);
    Route::post('/category', [productController::class, "storeCat"]);
    Route::post('/category/edit', [productController::class, "editCat"]);

    Route::get('/company', [productController::class, "company"]);
    Route::post('/company', [productController::class, "storeCoy"]);
    Route::post('/company/edit', [productController::class, "editCoy"]);

    Route::get('/products', [productController::class, "products"]);
    Route::post('/products', [productController::class, "storePro"]);
    Route::post('/product/edit', [productController::class, "editPro"]);
    Route::get('/products/get_pro', [productController::class, "getPro"]);
    Route::get('/product/delete/{id}', [productController::class, "deletePro"]);
    Route::get('/products/trashed', [productController::class, "trashedPro"]);
    Route::get('/product/restore/{id}', [productController::class, "restorePro"]);
    Route::post('/product/import', [productController::class, "import"]);
    Route::get('/product/status/{id}', [productController::class, "status"]);

    Route::get('/accounts', [AccountController::class, "accounts"]);
    Route::post('/accounts/{type}', [AccountController::class, "storeAccount"]);
    Route::get('/account/delete/{id}', [AccountController::class, "deleteAccount"]);
    Route::get('/accounts/statement/{id}', [AccountController::class, "statementView"]);
    Route::get('/accounts/details/{id}/{from}/{to}', [AccountController::class, "details"]);
    Route::post('/account/edit/{type}', [AccountController::class, "editAccount"]);
    Route::get('/account/statement/pdf/{id}/{from}/{to}', [AccountController::class, "downloadStatement"]);
    Route::post('/account/vendor/import', [AccountController::class, "vendorImport"]);
    Route::post('/account/customer/import', [AccountController::class, "customerImport"]);


    Route::get('/deposit', [AccountController::class, "deposit"]);
    Route::post('/deposit', [AccountController::class, "storeDeposit"]);


    Route::get('/withdraw', [AccountController::class, "withdraw"]);
    Route::post('/withdraw', [AccountController::class, "storeWithdraw"]);


    Route::get('/expense/{from?}/{to?}', [AccountController::class, "expense"]);
    Route::post('/expense/store', [AccountController::class, "storeExpense"]);


    Route::get('/transfer', [AccountController::class, "transfer"]);
    Route::post('/transfer', [AccountController::class, "storeTransfer"]);

    Route::get('/transfer/print/{ref}', [AccountController::class, "printTransfer"]);

    Route::get('/vendors', [AccountController::class, "vendors"]);
    Route::get('/customers', [AccountController::class, "customers"]);
    Route::get('/customer/purchaseDetails/{id}', [AccountController::class, "customersPurchase"]);
    Route::get('/customer/purchaseDetails/pdf/{id}', [AccountController::class, "customersPurchasePDF"]);

    Route::get('/purchase', [purchaseController::class, "purchase"]);
    Route::post('/purchase', [purchaseController::class, "storePurchase"]);
    Route::get('/purchase/store', [purchaseController::class, "StoreDraft"]);
    Route::get('/purchase/draft/items', [purchaseController::class, "draftItems"]);
    Route::get('/purchase/update/draft/qty/{id}/{qty}', [purchaseController::class, "updateDraftQty"]);
    Route::get('/purchase/update/draft/rate/{id}/{rate}', [purchaseController::class, "updateDraftRate"]);
    Route::get('/purchase/draft/delete/{id}', [purchaseController::class, "deleteDraft"]);
    Route::get('/purchase/history/{from?}/{to?}', [purchaseController::class, "history"]);
    Route::get('/purchase/pendings', [purchaseController::class, "pendings"])->name('purchasePendings');
    Route::post('/purchase/receive', [PurchaseReceivesController::class, "store"]);

    Route::get('/purchase/edit/{id}', [purchaseController::class, "edit"]);
    Route::get('/purchase/edit/items/{id}', [purchaseController::class, "editItems"]);
    Route::get('/purchase/edit/store/{id}', [purchaseController::class, "editAddItems"]);
    Route::get('/purchase/edit/delete/{id}', [purchaseController::class, "deleteEdit"]);
    Route::get('/purchase/update/edit/qty/{id}/{qty}', [purchaseController::class, "updateEditQty"]);
    Route::get('/purchase/update/edit/rate/{id}/{rate}', [purchaseController::class, "updateEditRate"]);

    Route::get('/sale', [SaleController::class, "sale"]);
    Route::post('/sale', [SaleController::class, "storeSale"]);
    Route::get('/sale/store', [saleController::class, "StoreDraft"]);
    Route::get('/sale/getPrice/{id}', [SaleController::class, "getPrice"]);
    Route::get('/sale/draft/items', [saleController::class, "draftItems"]);
    Route::get('/sale/update/draft/qty/{id}/{qty}', [saleController::class, "updateDraftQty"]);
    Route::get('/sale/update/draft/rate/{id}/{price}', [saleController::class, "updateDraftRate"]);
    Route::get('/sale/update/draft/discount/{id}/{price}', [saleController::class, "updateDraftDiscount"]);
    Route::get('/sale/draft/delete/{id}', [saleController::class, "deleteDraft"]);
    Route::get('/sale/history/{from?}/{to?}', [saleController::class, "history"]);
    Route::post('/sale/deliver', [SaleReceivesController::class, "store"]);

    Route::get('/sale/print/{ref}/{target}', [SaleController::class, 'print']);
    Route::get('/sale/inv/print/{ref}', [SaleController::class, 'invprint']);
    Route::get('/sale/printlast', [SaleController::class, 'printLast']);

    Route::get('/sale/edit/{id}', [saleController::class, "edit"]);
    Route::get('/sale/edit/items/{id}', [saleController::class, "editItems"]);
    Route::get('/sale/edit/store/{id}', [saleController::class, "editAddItems"]);
    Route::get('/sale/edit/delete/{id}', [saleController::class, "deleteEdit"]);
    Route::get('/sale/update/edit/qty/{id}/{qty}', [saleController::class, "updateEditQty"]);
    Route::get('/sale/update/edit/price/{id}/{price}', [saleController::class, "updateEditPrice"]);
    Route::get('/sale/update/edit/discount/{id}/{discount}', [saleController::class, "updateEditDiscount1"]);
    Route::get('/sale/update/discount/{id}/{discount}', [saleController::class, "updateEditDiscount"]);

    Route::get('/sale/returns', [ReturnedSalesController::class, 'index']);
    Route::get('/sale/returns/list', [ReturnedSalesController::class, 'view'])->name('returnsList');

    Route::get('/stock/{warehouse?}',[StockController::class, "stock"]);

    Route::get('/settings',[dashboardController::class, "settings"]);
    Route::post('/settings/language/update',[dashboardController::class, "changeLanguage"]);
    Route::post('/settings/profile/update',[dashboardController::class, "profileUpdate"]);
    Route::post('/settings/password/update',[dashboardController::class, "passwordUpdate"]);

    Route::get('/quotation', [QuotationController::class, "quotation"]);
    Route::post('/quotation', [QuotationController::class, "storeQuotation"]);

    Route::get('/quotation/details/{ref}', [QuotationController::class, "quotDetails"]);
    Route::get('/quotation/detail/list/{ref}', [QuotationController::class, "detailsList"]);
    Route::get('/quotation/store/', [QuotationController::class, "storeDetails"]);
    Route::get('/quotation/details/delete/{id}', [QuotationController::class, "deleteDetails"]);
    Route::get('/quotation/updateDiscount/{ref}/{discount}', [QuotationController::class, "updateDiscount"]);
    Route::get('/quotation/print/{ref}', [QuotationController::class, "print"]);
    Route::get('/quotation/edit/qty/{id}/{qty}', [QuotationController::class, "updateQty"]);
    Route::get('/quotation/edit/rate/{id}/{rate}/{discount}', [QuotationController::class, "updateRate"]);

    Route::get('/dashboard/customer_dues', [DashboardController::class, 'customer_d']);
    Route::get('/dashboard/vendors_dues', [DashboardController::class, 'vendors_d']);
    Route::get('/dashboard/today_sale', [DashboardController::class, 'today_sale']);
    Route::get('/dashboard/today_purchase', [DashboardController::class, 'today_purchase']);
    Route::get('/dashboard/today_expense', [DashboardController::class, 'today_expense']);
    Route::get('/dashboard/total_cash', [DashboardController::class, 'total_cash']);
    Route::get('/dashboard/today_cash', [DashboardController::class, 'today_cash']);
    Route::get('/dashboard/today_bank', [DashboardController::class, 'today_bank']);
    Route::get('/dashboard/total_bank', [DashboardController::class, 'total_bank']);
    Route::get('/dashboard/ledgerDetails', [DashboardController::class, 'ledgerDetails']);
    Route::get('/dashboard/incomeExpenseDetails', [DashboardController::class, 'incomeExpDetails']);
    Route::get('/dashboard/CashBook/{date}', [DashboardController::class, 'cashBook']);

    Route::get('/profit/{from}/{to}', [productController::class, 'profit']);

    Route::get('/return/{from?}/{to?}', [SaleReturnController::class, 'index']);
    Route::post('/returns', [SaleReturnController::class, 'search']);
    Route::get('/returns/view/{id}', [SaleReturnController::class, 'view']);
    Route::post('/return/save/{bill}', [SaleReturnController::class, 'saveReturn']);

    Route::get('/stockAlert', [reportController::class, 'stockAlert']);

    Route::get('/users', [usersController::class, 'index']);
    Route::post('/users/store', [usersController::class, 'store']);
    Route::post('/users/update', [usersController::class, 'update']);

    Route::get('/warehouses', [WarehousesController::class, 'index']);
    Route::post('/warehouses/store', [WarehousesController::class, 'store']);
    Route::post('/warehouses/update', [WarehousesController::class, 'update']);

    Route::get('/pos', [POSController::class, 'index']);
    Route::get('/pos/allProducts', [POSController::class, 'allProducts']);
    Route::get('/pos/byCategory/{id}', [POSController::class, 'byCategory']);
    Route::get('/pos/byBrand/{id}', [POSController::class, 'byBrand']);
    Route::get('/pos/getSingleProduct/{id}', [POSController::class, 'getSingleProduct']);
    Route::get('/pos/save', [POSController::class, 'store']);

    Route::get('/stocktransfer', [StockTransferController::class, 'index']);
    Route::post('/stocktransfer/create', [StockTransferController::class, 'create']);
    Route::post('/stocktransfer/store', [StockTransferController::class, 'store']);

    Route::get('/stocktransfer/getSingleProduct/{id}/{warehouse}', [StockTransferController::class, 'getSingleProduct']);
    Route::get('/stocktransfer/print/{id}', [StockTransferController::class, 'print']);
});

Route::middleware(['confirm.password'])->group(function () {
    Route::get('/sale/delete/{ref}', [saleController::class, "deleteSale"]);
    Route::get('/purchase/delete/{ref}', [purchaseController::class, "deletePurchase"]);
    Route::get('/deposit/delete/{ref}', [AccountController::class, "deleteDeposit"]);
    Route::get('/quotation/delete/{ref}', [QuotationController::class, "delete"]);
    Route::get('/withdraw/delete/{ref}', [AccountController::class, "deleteWithdraw"]);
    Route::get('/transfer/delete/{ref}', [AccountController::class, "deleteTransfer"]);
    Route::get('/expense/delete/{ref}', [AccountController::class, "deleteExpense"]);
    Route::get('/return/delete/{ref}', [SaleReturnController::class, 'delete']);
    Route::get('/stocktransfer/delete/{ref}', [StockTransferController::class, 'delete']);
    Route::get('/product/delete/{id}', [productController::class, "deletePro"]);
});
