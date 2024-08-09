<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\InventoryController;
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
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

Route::prefix('/Admin')->group( function(){
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
   // Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
   Route::middleware('auth:admin')->group(function(){
    Route::get('dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::get('user-dashbaord', [DashboardController::class, 'userDashboard'])->name('user_dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
   //Modules Routes
    route::match(['get','post'],'create-module',[ModuleController::class, 'createModule'])->name('module_create');
    route::get('view-module',[ModuleController::class, 'viewModules'])->name('module_view');
    route::get('module_edit/{id}',[ModuleController::class, 'viewModuleById']);
    route::post('module_edit',[ModuleController::class, 'editModule'])->name('module_edit');
    route::get('module_delete/{id}',[ModuleController::class, 'deleteModule'])->name('module_delete');
    // Roles Routes
//Inventory
    route::match(['get','post'],'create-inventory',[InventoryController::class, 'createInventory'])->name('inventory_create')->middleware('permission:inventory_create');
    route::get('view-inventory',[InventoryController::class, 'viewInventory'])->name('inventory_view')->middleware('permission:inventory_view');
    route::get('inventories/{id}',[InventoryController::class, 'viewInventoryDetails']);
    route::get('inventories/{id}/payments',[InventoryController::class, 'inventoryPaymentsHistory'])->name('inventory_payments')->middleware('permission:inventory_supplierPayments');
    route::post('inventory_payment',[InventoryController::class, 'inventoryPayment'])->name('inventory_pay')->middleware('permission:inventory_pay');
   // route::get('module_delete/{id}',[ModuleController::class, 'deleteModule'])->name('module_delete');
    // Roles Routes
    route::match(['get','post'],'create-role',[RoleController::class, 'createRole'])->name('role_create')->middleware('permission:role_create');
    route::get('View-role',[RoleController::class, 'viewRoles'])->name('role_view')->middleware('permission:role_view');
    route::get('Edit-Role/{id}',[RoleController::class, 'viewRoleById'])->name('role_viewSingle');
    route::post('Edit-Role',[RoleController::class, 'editRole'])->name('role_edit')->middleware('permission:role_edit');
    route::get('Delete-Role/{id}',[RoleController::class, 'deleteRole'])->name('role_delete')->middleware('permission:role_delete');

     // Permissions Routes
     route::match(['get','post'],'create-permission',[PermissionController::class, 'createPermission'])->name('permission_create');
     route::get('view-permission',[PermissionController::class, 'viewPermission'])->name('permission_view');
     route::get('permission_edit/{id}',[PermissionController::class, 'viewPermissionById']);
     route::post('permission_edit',[PermissionController::class, 'editPermission'])->name('permission_edit');
     route::get('permission_delete/{id}',[PermissionController::class, 'deletePermission'])->name('permission_delete');
     //Asign permissions
     route::get('Roles/{role}/Permissions/',[RoleController::class, 'displayPermissions'])->name('role_display_permissions');
     route::post('Roles/{role}/Permissions', [RoleController::class, 'asignPermission'])->name('role_asign_permission')->middleware('permission:role_asign_permission');
    //Employee Routes
    route::get('admins/{admin}/roles/',[EmployeeController::class, 'displayRoles'])->name('user_displayRoles');
    route::post('admins/{admin}/roles', [EmployeeController::class, 'asignRole'])->name('user_asignRole')->middleware('permission:user_asignRole');
    route::match(['get','post'],'create-user',[EmployeeController::class, 'createUser'])->name('user_create'); 
    route::match(['get','post'],'change-password',[EmployeeController::class, 'changePassword'])->name('user_changePasword')->middleware('permission:user_changePasword'); 
    route::get('reset-password/{id}',[EmployeeController::class, 'resetPassword'])->name('user_resetPassword')->middleware('permission:user_resetPassword');
    route::match(['get','post'],'modify-masterAccount',[EmployeeController::class, 'modifyMasterAccount'])->name('user_masterAccounnt')->middleware('permission:user_modifyMasterAccount');  
    route::get('View-users',[EmployeeController::class, 'viewUsers'])->name('user_view'); 
        route::get('users/{id}',[EmployeeController::class, 'viewUserDetailsById']);
        route::get('user-profile',[EmployeeController::class, 'userProfile'])->name('user_profile')->middleware('permission:user_profile');
    route::get('edit-user/{id}',[EmployeeController::class, 'viewUserById'])->name('user_viewSingle');
    route::post('user_edit',[EmployeeController::class, 'editUser'])->name('user_edit')->middleware('permission:user_edit');
   // route::post('Roles/{role}/Permissions', [RoleController::class, 'asignPermission'])->name('Role_asign_permission');
    //Products Routes
    route::match(['get','post'],'create-product',[ProductController::class, 'createProduct'])->name('product_create')->middleware('permission:product_create');
    route::post('upload-product',[ProductController::class, 'ProductExcelUpload'])->name('products_upload')->middleware('permission:products_upload');
    route::get('view-product',[ProductController::class, 'viewAllProducts'])->name('product_view')->middleware('permission:product_view');
    route::get('print-barcode/{barcodeValue}',[ProductController::class, 'printBarcode'])->name('print_barcode')->middleware('permission:print_barcode');
    route::get('product-lowQuantity',[ProductController::class, 'viewProductsWithLowQuantity'])->name('product_lowQuantity');
    route::get('product-expiredOrExpiring',[ProductController::class, 'viewExpiredOrExpiringProducts'])->name('product_expiredOrExpiring');
    // route::get('/products/{barcode}', [ProductController::class, 'findByBarcode'])->name('product.scan');
    //route::get('/products', [ProductController::class, 'searchByName'])->name('product.search');

    

   route::get('/products/{name}', [ProductController::class, 'searchByName'])->name('product_search');
   route::get('/products/quantity/{id}', [ProductController::class, 'getProductById'])->name('product_single');
   route::get('/products/byBarcode/{barcode}', [ProductController::class, 'findByBarcode'])->name('product_barcode');
   
   route::get('product_edit/{id}',[ProductController::class, 'viewProductById']);
   
     route::post('product_edit',[ProductController::class, 'editProduct'])->name('product_edit')->middleware('permission:product_edit');
     route::get('product_delete/{id}',[ProductController::class, 'deleteProduct'])->name('product_delete')->middleware('permission:product_delete');;
   //sales Routes
route::get('/sales/create', [SaleController::class, 'createSales'])->name('sales_create')->middleware('permission:sales_create');
route::post('/sales', [SaleController::class, 'completeTransaction'])->name('sales_save');
Route::get('/receipt/{sale_id}', [SaleController::class, 'showReceipt'])->name('receipt_show');

route::get('/sales/{saleId}/items', [SaleController::class, 'getSaleItems'])->name('sales_items');
//route::get('/sales', [SaleController::class, 'ViewAllSales'])->name('sales_view');
route::get('/sales/filter', [SaleController::class, 'filterSales'])->name('sales_view')->middleware('permission:sales_view');
route::get('/sales/report', [SaleController::class, 'filterSalesByLoginUser'])->name('sales_cashierAccount')->middleware('permission:sales_cashierAccount');;
//route::get('/sales/report/items', [SaleController::class, 'filterSales'])->name('sales_report');
route::get('/sales/filter/index', [SaleController::class, 'showFilterPage'])->name('sales_index');
 //Supplier Routes
 route::match(['get','post'],'create-supplier',[SupplierController::class, 'createSupplier'])->name('supplier_create')->middleware('permission:supplier_create');;
 route::get('view-supplier',[SupplierController::class, 'viewSuppliers'])->name('supplier_view')->middleware('permission:supplier_view');;
 route::get('supplier_edit/{id}',[SupplierController::class, 'viewSupplierById']);
 route::post('supplier_edit',[SupplierController::class, 'editSupplier'])->name('supplier_edit')->middleware('permission:supplier_edit');;
 route::get('supplier_delete/{id}',[SupplierController::class, 'deleteSupplier'])->name('supplier_delete')->middleware('permission:supplier_delete');;
});
  
});

