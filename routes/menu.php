<?php

use Illuminate\Support\Facades\Route;
use Hexatex\LaravelMenu\Http\Controllers\Admin;
use Hexatex\LaravelMenu\Http\Controllers\Web;

Route::bindMenuModel();

// Admin Routes
Route::middleware('can:admin-features.access')->group(function () {

    // Api
    Route::prefix('admin/api')->middleware('api')->group(function () {
        Route::post('menu/index', [Admin\Api\MenuController::class, 'index'])->middleware('can:menu.index')->name('admin.menu.index');
        Route::get('menu/{menu}', [Admin\Api\MenuController::class, 'get'])->middleware('can:view,menu')->name('admin.menu.get');
        Route::post('menu', [Admin\Api\MenuController::class, 'store'])->middleware('can:menu.store')->name('admin.menu.store');
        Route::put('menu/{menu}', [Admin\Api\MenuController::class, 'update'])->middleware('can:update,menu')->name('admin.menu.update');
        Route::delete('menu/{menu}', [Admin\Api\MenuController::class, 'destroy'])->middleware('can:destroy,menu')->name('admin.menu.destroy');
    });

    // Web
    Route::prefix('admin')->middleware('web')->group(function () {
        Route::get('menu', [Admin\MenuController::class, 'index'])->middleware('can:menu.index')->name('admin.web.menu.index');
        Route::get('menu/{menu}', [Admin\MenuController::class, 'show'])->middleware('can:view,menu')->name('admin.web.menu.show');
    });
});
