<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\VideoPostController;
use App\Http\Controllers\CommentController;

// Новости
Route::prefix('news')->group(function () {
    Route::post('/',            [NewsController::class, 'store']);    // создание новости
    Route::get('/',             [NewsController::class, 'index']);     // список новостей + их комментарии
    Route::get('/{news}',       [NewsController::class, 'show']);     // одна новость + её комментарии
});

// Видео-посты (аналогично новостям)
Route::prefix('video-posts')->group(function () {
    Route::get('/',             [VideoPostController::class, 'index']);
    Route::post('/',            [VideoPostController::class, 'store']);
    Route::get('/{videoPost}',  [VideoPostController::class, 'show']);
});

// Комментарии
Route::prefix('comments')->name('comments.')->group(function () {

    // Список комментариев к конкретной сущности
    // GET    /api/comments?type=news&id=15
    // GET    /api/comments?type=videoPost&id=8
    Route::get('/', [CommentController::class, 'index'])
        ->name('index');

    // Создание комментария (к новости / видео)
    // POST   /api/comments
    Route::post('/', [CommentController::class, 'store'])
        ->name('store');

    // Редактирование своего комментария
    // PATCH  /api/comments/123
    Route::patch('/{comment}', [CommentController::class, 'update'])
        ->name('update')
        ->whereNumber('comment');

    // Удаление комментария
    // DELETE /api/comments/123
    Route::delete('/{comment}', [CommentController::class, 'destroy'])
        ->name('destroy')
        ->whereNumber('comment');
});
