<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    AssetController,
    SearchController,
    ExportController,
    ReportController,
    RegionController,
    VendorController,
    ProfileController,
    InvoiceController,
    SettingsController,
    LocationController,
    DashboardController,
    AuditTrailController,
    DepartmentController,
    InsuranceController,
    NotificationController,
    RolePermissionController,
    AssetCategoryController,
    AssetAssignmentController,
    InsuranceReportController
};

// Landing page route
Route::get('/', function () {
    return view('auth.login');
})->name('landing');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Asset Assignments
Route::get('/asset-assignments', [AssetAssignmentController::class, 'index'])->name('asset-assignments.index');
Route::delete('/asset-assignments/clear', [AssetAssignmentController::class, 'clear'])->name('asset-assignments.clear');

// Reports
Route::get('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');
Route::get('/reports/export/{format}', [ReportController::class, 'export'])->name('reports.export');
Route::get('/reports/export/filtered', [ReportController::class, 'exportFiltered'])->name('reports.export.filtered');

// Assets
Route::get('/assets/filter', [AssetController::class, 'filterByInvoiceNumber'])->name('assets.filterByInvoiceNumber');
Route::get('/assets/{id}/it-control-form', [AssetController::class, 'itControlForm'])->name('assets.itControlForm');
Route::get('/assets/{id}/export-pdf', [AssetController::class, 'exportToPdf'])->name('assets.exportToPdf');
Route::get('/assets/report', [AssetController::class, 'report'])->name('assets.report');
Route::get('/assets/report/pdf', [AssetController::class, 'exportPdf'])->name('assets.report.pdf');
Route::get('/assets/report/excel', [AssetController::class, 'exportExcel'])->name('assets.report.excel');
Route::post('/assets/import', [AssetController::class, 'import'])->name('assets.import');
Route::get('/assets/total_count', [AssetController::class, 'getTotalAssetCount'])->name('assets.total_count');
Route::get('/search/assets', [AssetController::class, 'search'])->name('assets.search');
Route::post('/transfer-temp-assets', [AssetController::class, 'transferTempAssetsToAssets']);

// Invoices
Route::get('/invoices/{id}/file', [InvoiceController::class, 'viewFile'])->name('invoices.viewFile');

// Insurance Reports
Route::get('/insurances/report', [InsuranceReportController::class, 'report'])->name('insurance.report');

Route::get('/insurances/export/pdf', [InsuranceReportController::class, 'exportPdf'])->name('insurances.export.pdf');
Route::get('/insurances/export/excel', [InsuranceReportController::class, 'exportExcel'])->name('insurances.export.excel');
Route::get('/insurances/export/word', [InsuranceReportController::class, 'exportWord'])->name('insurances.export.word');

// Audit Trails
Route::get('/audit-trails', [AuditTrailController::class, 'index'])->name('audit-trails.index');
Route::delete('/audit-trails/clear', [AuditTrailController::class, 'clear'])->name('audit-trails.clear');

// Notifications
Route::delete('/notifications/clearAll', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');

// Middleware-protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/markAsRead/{id}', [NotificationController::class, 'markAsReadSingle'])->name('notifications.markAsReadSingle');
    Route::post('/notifications/markAsRead', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/perform-task', [SettingsController::class, 'performTask'])->name('settings.performTask');

    // Resource routes
    Route::resource('vendors', VendorController::class);
    Route::resource('asset_categories', AssetCategoryController::class);
    Route::resource('assets', AssetController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('regions', RegionController::class);
    Route::resource('users', UserController::class);
    Route::resource('insurances', InsuranceController::class);
    Route::resource('invoices', InvoiceController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Roles and Permissions
    Route::prefix('roles-permissions')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('roles_permissions.index');
        Route::get('/create-role', [RolePermissionController::class, 'createRole'])->name('roles_permissions.create_role');
        Route::post('/store-role', [RolePermissionController::class, 'storeRole'])->name('roles_permissions.store_role');
        Route::get('/create-permission', [RolePermissionController::class, 'createPermission'])->name('roles_permissions.create_permission');
        Route::post('/store-permission', [RolePermissionController::class, 'storePermission'])->name('roles_permissions.store_permission');
        Route::get('/destroy-role/{id}', [RolePermissionController::class, 'destroyRole'])->name('roles_permissions.destroy_role');
        Route::get('/destroy-permission/{id}', [RolePermissionController::class, 'destroyPermission'])->name('roles_permissions.destroy_permission');
    });
});

// Authentication routes
require __DIR__.'/auth.php';
