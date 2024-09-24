<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apps\Dashboard;
use App\Http\Controllers\apps\FillDropdown;
use App\Http\Controllers\apps\MemberProfile;
use App\Http\Controllers\apps\Members;
use App\Http\Controllers\apps\MembersTree;

use App\Http\Controllers\apps\LevelSetting;
use App\Http\Controllers\InvoiceAddController;
use App\Http\Controllers\InvoiceListController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\apps\InvoiceList;
use App\Http\Controllers\apps\InvoiceAdd;

// Main Page Route
// Route::group([
//   'middleware' => 'exist.check'
// ], function () {
//   // Route::get('/', [Analytics::class, 'index'])->name('dashboard');
//   Route::get('/dashboard/analytics', [Analytics::class, 'index'])->name('dashboard-analytics');
//   Route::get('/dashboard/crm', [Crm::class, 'index'])->name('dashboard-crm');

// });

// authentication
// Route::get('/auth/reset-password-basic', [ResetPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
// Route::get('/auth/reset-password-cover', [ResetPasswordCover::class, 'index'])->name('auth-reset-password-cover');
// Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
// Route::get('/auth/forgot-password-cover', [ForgotPasswordCover::class, 'index'])->name('auth-forgot-password-cover');

// charts
// Route::get('/charts/apex', [ApexCharts::class, 'index'])->name('charts-apex');
// Route::get('/charts/chartjs', [ChartJs::class, 'index'])->name('charts-chartjs');


// aplus2 routes




// Route::middleware(['auth'])->group(function () {

//   //Level Setting Route
//   Route::prefix('/app/level')->group(function () {
//     Route::get('/', [LevelSetting::class, 'index'])->name('level');
//     Route::get('/list', [LevelSetting::class, 'getLevelList']);
//     Route::post('/add', [LevelSetting::class, 'addLevel']);
//     Route::Post('/edit', [LevelSetting::class, 'editLevel']);
//     Route::post('/delete', [LevelSetting::class, 'deleteLevel']);
//   });

//   //dropdown fill Route
//   Route::prefix('/filldropdown')->group(function () {
//     Route::get('roles', [FillDropdown::class, 'roles']);
//     Route::get('levels', [FillDropdown::class, 'levels']);
//     Route::get('users', [FillDropdown::class, 'users']);
//     Route::get('banks', [FillDropdown::class, 'banks']);
//   });

//   //Member Tree Route
//   Route::prefix('/app/members-tree')->group(function () {
//     Route::get('/', [MembersTree::class, 'index'])->name('app-members-tree');
//     Route::get('/list', [MembersTree::class, 'getMemberTree']);
//     Route::get('/downline', [MembersTree::class, 'getMemberDownline']); // get downline of passed member id
//   });

//   //Member Profile
//   Route::prefix('/app/member-profile')->group(function () {
//     Route::get('/', [MemberProfile::class, 'index'])->name('member-profile');
//     Route::get('/member-info', [MemberProfile::class, 'memberInfo'])->name('member-profile-info');
//     Route::post('/edit-password', [MemberProfile::class, 'editPassword']);
//   });

//   // Route::get('/', [Dashboard::class, 'index'])->name('dashboard');

//   //dashboard
//   Route::prefix('/app/dashboard')->group(function () {
//     // Route::get('/', [Dashboard::class, 'index'])->name('dashboard');
//     Route::post('/sales', [Dashboard::class, 'sales']);
//     Route::post('/commission', [Dashboard::class, 'commission']);
//     Route::get('/level-data', [Dashboard::class, 'getLevelData']);
//   });


//   //sale route
//   Route::post('/app/invoice/list', [InvoiceAddController::class, 'submitForm'])->name('insert-invoice');
//   Route::get('/get-all-data', [InvoiceListController::class, 'getAllData']);
//   Route::post('/delete-sale', [InvoiceListController::class, 'deleteSale']);
//   Route::get('/app/invoice/edit/{saleId}', [InvoiceListController::class, 'editView'])->name('edit-view');
//   Route::get('app/invoice/view-details/{saleId}', [InvoiceListController::class, 'viewDetails'])->name('view-sale-detail');
//   Route::get('/app/invoice/add', [InvoiceAddController::class, 'showDropdown'])->name('add-invoice');
//   Route::get('app/invoice/list', [InvoiceListController::class, 'index'])->name('app-invoice-list');
//   Route::post('/get-sale-id', [InvoiceListController::class, 'receiveSaleCheckbox'])->name('get-sale-id');
//   Route::post('/add-sale-payment', [InvoiceAddController::class, 'insert_sale_payment'])->name('add-sale-payment');
//   Route::patch('/update-sale', [InvoiceListController::class, 'update_sale'])->name('update-sale');

//   //commission
//   Route::prefix('/app/commission')->group(function () {
//     Route::get('/list/o', [CommissionController::class, 'view_o'])->name('app-commission-list-o');
//     Route::get('/list/pd', [CommissionController::class, 'view_pd'])->name('app-commission-list-pd');
//     Route::get('/get-pd', [CommissionController::class, 'getPD']);
//     Route::get('/get-o', [CommissionController::class, 'getO']);
//     Route::post('/get-commission-id', [CommissionController::class, 'receiveCommissionCheckbox'])->name('get-commission-id');
//     // Route::get('/list', [CommissionController::class, 'view_o'])->name('app-commission-list-o');
//     // Route::get('/preview', [InvoicePreview::class, 'index'])->name('app-invoice-preview');
//     // Route::get('/print', [InvoicePrint::class, 'index'])->name('app-invoice-print');
//     // Route::get('/edit', [InvoiceEdit::class, 'index'])->name('app-invoice-edit');
//     Route::post('/add-commission', [CommissionController::class, 'insert_payment'])->name('add-commission');
//   });

//   Route::prefix('/app/payment')->group(function () {
//     Route::get('/list', [PaymentController::class, 'view'])->name('app-payment-list');
//     Route::get('/get-payment-list', [PaymentController::class, 'get']);
//     Route::post('/delete-payment', [PaymentController::class, 'delete']);
//     Route::get('/view-details/{saleId}', [PaymentController::class, 'viewDetails'])->name('view-sale-detail');
//     Route::get('/edit/{saleId}', [PaymentController::class, 'editDetails'])->name('edit-sale');
//   });

// });
