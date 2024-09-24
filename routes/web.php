<?php

use App\Http\Controllers\MasterUsersController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\apps\FillDropdown;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\apps\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\apps\PermissionController;
use App\Http\Controllers\MerchantProfileController;

use App\Http\Controllers\TyreBrandController;
use App\Http\Controllers\TyreBrandModelController;
use App\Http\Controllers\TyreBrandModelSizeController;

use App\Http\Controllers\FleetBrandController;
use App\Http\Controllers\FleetBrandModelController;
use App\Http\Controllers\FleetAxleController;
use App\Http\Controllers\FleetTyreController;
use App\Http\Controllers\FleetConfigurationController;
use App\Http\Controllers\FleetCategoryController;
use App\Http\Controllers\FleetSegmentController;
use App\Http\Controllers\FleetGoodsController;
use App\Http\Controllers\FleetTyreLogController;
use App\Http\Controllers\FleetTyreInspectionController;

use App\Http\Controllers\FleetDetailController;
use App\Http\Controllers\FleetController;

// Authentication
Route::get('/auth/login', [AuthController::class, 'index'])->name('auth-login');
Route::group([
  'prefix' => 'auth'
], function () {
  Route::post('/login', [AuthController::class, 'login']);
});
Route::group([
  'middleware' => 'auth:api'
], function () {
  Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {

  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
  Route::resource('/dashboard', DashboardController::class);

  Route::prefix('/app')->group(function () {


    // Admin permissions
    Route::prefix('/permission')->group(function () {
      Route::get('/', [PermissionController::class, 'index'])->name('permission');
      Route::get('/list', [PermissionController::class, 'getPermissionList']); // get permission list with assigned to
      Route::post('/add', [PermissionController::class, 'addPermission']);
      Route::post('/edit', [PermissionController::class, 'editPermission']);
      Route::post('/delete', [PermissionController::class, 'deletePermission']);
    });

    // Merchant Route
    Route::resource('/merchant', MerchantController::class);
    Route::post('/merchant/update/{merchant_id}', [MerchantController::class, 'update']);
    Route::get('/merchant-list', [MerchantController::class, 'list']);
    Route::get('/switch-merchant/{merchant_id}', [MerchantController::class, 'switch'])->name('merchant.switch');
    Route::get('/merchant-render', [MerchantController::class, 'switch_render'])->name('merchant.switch_render');
    Route::get('/merchant-key-update', [MerchantController::class, 'updateNullMerchantEncryptIds'])->name('merchant.key_update');

    Route::prefix('/merchant-profile')->group(function () {
      Route::get('/', [MerchantProfileController::class, 'index'])->name('merchant-profile');
    });

  });


  Route::prefix('/setting')->group(function () {
    Route::get('/change-password', [SettingController::class, 'changePassword'])->name('change-password.index');
    Route::get('/list', [SettingController::class, 'list']);
    Route::post('/update-password', [SettingController::class, 'updatePassword']);
    Route::get('/merchant-edit', [SettingController::class, 'merchantEdit'])->name('merchant-edit.index');
    Route::get('/merchant-list', [SettingController::class, 'merchantList']);
    Route::post('/update-merchant/{merchant_id}', [SettingController::class, 'updateMerchant']);

    Route::prefix('/role')->group(function () {
      Route::get('/', [RoleController::class, 'index'])->name('setting-role');
      Route::get('/list', [RoleController::class, 'getRolesList']);
      Route::Post('/add', [RoleController::class, 'addRole']);
      Route::post('/edit', [RoleController::class, 'editRole']);
      Route::post('/delete', [RoleController::class, 'deleteRole']);
      Route::get('/get-permission-list', [PermissionController::class, 'getPermissions']);
    });

    Route::prefix('/user')->group(function () {
      Route::get('/', [UserController::class, 'index'])->name('setting-user');
      Route::get('/list', [UserController::class, 'list']);
      Route::Post('/add', [UserController::class, 'addUser']);
      Route::Post('/edit', [UserController::class, 'editUser']);
      Route::Post('/delete', [UserController::class, 'deleteUser']);

      Route::post('/update-user-status', [UserController::class, 'updateUserStatus']);
    });
  });


  // Dropdown Fill Route
  Route::prefix('/filldropdown')->group(function () {
    Route::get('merchant', [FillDropdown::class, 'merchant']);
    Route::get('product-category', [FillDropdown::class, 'productCategory']);
    Route::get('role', [FillDropdown::class, 'roles']);
    Route::get('user', [FillDropdown::class, 'users']);
  });

  Route::get('/tyre-brand', [TyreBrandController::class, 'index'])->name('tyre-brand');
  Route::get('/tyre-brand/list', [TyreBrandController::class, 'list']);
  Route::post('/tyre-brand/create', [TyreBrandController::class, 'create']);
  Route::post('/tyre-brand/update', [TyreBrandController::class, 'update']);
  Route::post('/tyre-brand/delete', [TyreBrandController::class, 'delete']);

  Route::get('/tyre-brand-model/list', [TyreBrandModelController::class, 'list']);
  Route::post('/tyre-brand-model/create', [TyreBrandModelController::class, 'create']);
  Route::post('/tyre-brand-model/update', [TyreBrandModelController::class, 'update']);
  Route::post('/tyre-brand-model/delete', [TyreBrandModelController::class, 'delete']);

  Route::get('/tyre-brand-model-size/list', [TyreBrandModelSizeController::class, 'list']);
  Route::get('/tyre-brand-model-size/get', [TyreBrandModelSizeController::class, 'get']);
  Route::post('/tyre-brand-model-size/create', [TyreBrandModelSizeController::class, 'create']);
  Route::post('/tyre-brand-model-size/update', [TyreBrandModelSizeController::class, 'update']);
  Route::post('/tyre-brand-model-size/delete', [TyreBrandModelSizeController::class, 'delete']);

  Route::get('/fleet-brand', [FleetBrandController::class, 'index'])->name('fleet-brand');
  Route::get('/fleet-brand/list', [FleetBrandController::class, 'list']);
  Route::post('/fleet-brand/create', [FleetBrandController::class, 'create']);
  Route::post('/fleet-brand/update', [FleetBrandController::class, 'update']);
  Route::post('/fleet-brand/delete', [FleetBrandController::class, 'delete']);

  Route::get('/fleet-brand-model/list', [FleetBrandModelController::class, 'list']);
  Route::post('/fleet-brand-model/create', [FleetBrandModelController::class, 'create']);
  Route::post('/fleet-brand-model/update', [FleetBrandModelController::class, 'update']);
  Route::post('/fleet-brand-model/delete', [FleetBrandModelController::class, 'delete']);

  Route::get('/fleet-configuration', [FleetConfigurationController::class, 'index'])->name('fleet-configuration');
  Route::get('/fleet-configuration/list', [FleetConfigurationController::class, 'list']);
  Route::post('/fleet-configuration/create', [FleetConfigurationController::class, 'create']);
  Route::post('/fleet-configuration/update', [FleetConfigurationController::class, 'update']);
  Route::post('/fleet-configuration/delete', [FleetConfigurationController::class, 'delete']);

  Route::get('/fleet-category', [FleetCategoryController::class, 'index'])->name('fleet-category');
  Route::get('/fleet-category/list', [FleetCategoryController::class, 'list']);
  Route::post('/fleet-category/create', [FleetCategoryController::class, 'create']);
  Route::post('/fleet-category/update', [FleetCategoryController::class, 'update']);
  Route::post('/fleet-category/delete', [FleetCategoryController::class, 'delete']);

  Route::get('/fleet-segment', [FleetSegmentController::class, 'index'])->name('fleet-segment');
  Route::get('/fleet-segment/list', [FleetSegmentController::class, 'list']);
  Route::post('/fleet-segment/create', [FleetSegmentController::class, 'create']);
  Route::post('/fleet-segment/update', [FleetSegmentController::class, 'update']);
  Route::post('/fleet-segment/delete', [FleetSegmentController::class, 'delete']);

  Route::get('/fleet-good', [FleetGoodsController::class, 'index'])->name('fleet-good');
  Route::get('/fleet-good/list', [FleetGoodsController::class, 'list']);
  Route::post('/fleet-good/create', [FleetGoodsController::class, 'create']);
  Route::post('/fleet-good/update', [FleetGoodsController::class, 'update']);
  Route::post('/fleet-good/delete', [FleetGoodsController::class, 'delete']);

  Route::get('/fleet', [FleetController::class, 'index'])->name('fleet');
  Route::get('/fleet/list', [FleetController::class, 'list']);
  Route::post('/fleet/create', [FleetController::class, 'create']);
  Route::post('/fleet/update', [FleetController::class, 'update']);
  Route::post('/fleet/delete', [FleetController::class, 'delete']);

  Route::get('/fleet-axle/list', [FleetAxleController::class, 'list']);
  Route::post('/fleet-axle/create', [FleetAxleController::class, 'create']);
  Route::post('/fleet-axle/update', [FleetAxleController::class, 'update']);
  Route::post('/fleet-axle/delete', [FleetAxleController::class, 'delete']);

  Route::get('/fleet-tyre', [FleetTyreController::class, 'index'])->name('fleet-tyre');
  Route::get('/fleet-tyre/get', [FleetTyreController::class, 'get']);
  Route::get('/fleet-tyre/list', [FleetTyreController::class, 'list']);
  Route::post('/fleet-tyre/create', [FleetTyreController::class, 'create']);
  Route::post('/fleet-tyre/update', [FleetTyreController::class, 'update']);
  Route::post('/fleet-tyre/delete', [FleetTyreController::class, 'delete']);
  Route::get('/fleet-tyre/list-dropdown', [FleetTyreController::class, 'listDropdown']);
  Route::post('/fleet-tyre/set', [FleetTyreController::class, 'setTyre']);
  Route::post('/fleet-tyre/unset', [FleetTyreController::class, 'unsetTyre']);
  Route::post('/fleet-tyre/update-position', [FleetTyreController::class, 'updatePosition']);

  Route::get('/fleet-detail/{id}', [FleetDetailController::class, 'index'])->name('fleet-detail');
  Route::post('/fleet-detail/install', [FleetDetailController::class, 'install']);
  Route::post('/fleet-detail/unmount', [FleetDetailController::class, 'unmount']);
  Route::post('/fleet-detail/getcharts', [FleetDetailController::class, 'getcharts']);

  Route::get('/fleet-tyre-log/list', [FleetTyreLogController::class, 'list']);

  Route::get('/fleet-tyre-inspection/list', [FleetTyreInspectionController::class, 'list']);
  Route::post('/fleet-tyre-inspection/create', [FleetTyreInspectionController::class, 'create']);
  Route::post('/fleet-tyre-inspection/delete', [FleetTyreInspectionController::class, 'delete']);
  Route::post('/fleet-tyre-inspection/update', [FleetTyreInspectionController::class, 'update']);


  Route::get('/dashboard-charts/getCharts', [DashboardController::class, 'getCharts']);
  Route::get('/dashboard-charts/getTyreCPKCharts', [DashboardController::class, 'getTyreCPKCharts']);

});
