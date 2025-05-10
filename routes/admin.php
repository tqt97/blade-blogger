<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
    // category
    Route::delete('categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::resource('categories', CategoryController::class);

    // post
    Route::delete('posts/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulk-delete');
    Route::resource('posts', PostController::class);
});
