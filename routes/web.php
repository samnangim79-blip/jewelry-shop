<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Admin\Branches\Index as BranchesIndex;
use App\Livewire\Admin\Companies\Index as CompaniesIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Permissions\Index as PermissionsIndex;
use App\Livewire\Admin\Roles\Index as RolesIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Warehouses\Index as WarehousesIndex;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        Route::get('/companies', CompaniesIndex::class)
            ->middleware('permission:companies.view')
            ->name('companies.index');

        Route::get('/branches', BranchesIndex::class)
            ->middleware('permission:branches.view')
            ->name('branches.index');

        Route::get('/warehouses', WarehousesIndex::class)
            ->middleware('permission:warehouses.view')
            ->name('warehouses.index');

        Route::get('/users', UsersIndex::class)
            ->middleware('permission:users.view')
            ->name('users.index');

        Route::get('/roles', RolesIndex::class)
            ->middleware('permission:roles.view')
            ->name('roles.index');

        Route::get('/permissions', PermissionsIndex::class)
            ->middleware('permission:permissions.view')
            ->name('permissions.index');
    });
});
