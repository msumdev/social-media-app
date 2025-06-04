<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Authentication\ForgottenPasswordController;
use App\Http\Controllers\Authentication\RegistrationConfirmationController;
use App\Http\Controllers\Authentication\RegistrationController;
use App\Http\Controllers\Authentication\RegistrationPrivacyPolicyController;
use App\Http\Controllers\Authentication\RegistrationRulesController;
use App\Http\Controllers\Authentication\ResetPasswordController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Chat\ChatFriendListController;
use App\Http\Controllers\Chat\Room\RoomController;
use App\Http\Controllers\Chat\Room\RoomInfoController;
use App\Http\Controllers\Chat\Room\RoomMessageController;
use App\Http\Controllers\Chat\Room\RoomMessageReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\User\Comments\UserProfileCommentController;
use App\Http\Controllers\User\Interests\InterestController;
use App\Http\Controllers\User\Languages\LanguageController;
use App\Http\Controllers\User\Logs\UserProfileLogsController;
use App\Http\Controllers\User\Posts\PostCommentController;
use App\Http\Controllers\User\Posts\PostCommentReactionController;
use App\Http\Controllers\User\Posts\PostController;
use App\Http\Controllers\User\Posts\PostLikeController;
use App\Http\Controllers\User\Posts\PostReportController;
use App\Http\Controllers\User\ReportUser\ReportUserController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserProfileController;

// Login Routes
Route::get('/login', [AuthenticationController::class, 'render'])->name('login');
Route::get('/logout', [AuthenticationController::class, 'destroy'])->name('login.destroy');
Route::post('/login', [AuthenticationController::class, 'store'])->name('login.create');

// Forgotten Password Routes
Route::get('/forgotten-password', [ForgottenPasswordController::class, 'render'])->name('forgotten-password.render');
Route::post('/forgotten-password', [ForgottenPasswordController::class, 'store'])->name('forgotten-password.store');
Route::get('/forgotten-password/{token}', [ResetPasswordController::class, 'render'])->name('reset-password.render');
Route::post('/forgotten-password/{token}', [ResetPasswordController::class, 'store'])->name('reset-password.store');

// Registration Routes
Route::get('/registration', [RegistrationController::class, 'render'])->name('registration.render');
Route::post('/registration', [RegistrationController::class, 'store'])->name('registration.create');
Route::get('/registration/confirm/{token}', [RegistrationConfirmationController::class, 'render'])->name('registration-confirmation.render');
Route::get('/registration/{id}/resend', [RegistrationConfirmationController::class, 'resend'])->name('registration.resend');
Route::get('/registration/rules', [RegistrationRulesController::class, 'render'])->name('registration.rules.render');
Route::get('/registration/privacy-policy', [RegistrationPrivacyPolicyController::class, 'render'])->name('registration.privacy-policy.render');

Route::middleware('auth')->group(function () {
    Route::prefix('post')->group(function () {
        Route::get('/', [PostController::class, 'all'])->name('post');
        Route::post('/', [PostController::class, 'create'])->name('post.create');
        Route::delete('/{id}', [PostController::class, 'delete'])->name('post.delete');

        Route::post('/{id}/report', [PostReportController::class, 'create'])->name('post.report.create');

        Route::get('/{id}/comments', [PostCommentController::class, 'index'])->name('post.comment.index');
        Route::post('/{id}/comment', [PostCommentController::class, 'create'])->name('post.comment.create');
        Route::delete('/{id}/comment/{comment_id}', [PostCommentController::class, 'destroy'])->name('post.comment.destroy');
        Route::post('{id}/{comment_id}/react', [PostCommentReactionController::class, 'toggle'])->name('post.comment.react.toggle');

        Route::prefix('likes')->group(function () {
            Route::get('/{id}', [PostLikeController::class, 'index'])->name('post.likes');
            Route::post('/{id}', [PostLikeController::class, 'toggle'])->name('post.likes.toggle');
        });
    });

    Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');
    Route::get('interests', [InterestController::class, 'index'])->name('interests.index');

    Route::prefix('/logs')->group(function () {
        Route::get('/access', [UserProfileLogsController::class, 'access'])->name('logs.access');
    });

    Route::get('/reports', [ReportUserController::class, 'index'])->name('user.report.index');
    Route::get('/report-reasons', [ReportUserController::class, 'reasons'])->name('user.report.reasons');

    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/room', [RoomController::class, 'index'])->name('chat.room.index');
        Route::get('/room/{id}', [RoomController::class, 'show'])->name('chat.room.show');
        Route::post('/room', [RoomController::class, 'create'])->name('chat.room.create');
        Route::patch('/room/{id}', [RoomController::class, 'update'])->name('chat.room.update');
        Route::delete('/room/{id}', [RoomController::class, 'delete'])->name('chat.room.delete');

        Route::get('/room/{id}/info', [RoomInfoController::class, 'index'])->name('chat.room.info.index');

        Route::get('/room/{id}/message', [RoomMessageController::class, 'index'])->name('chat.room.message.index');
        Route::post('/room/{id}/message', [RoomMessageController::class, 'create'])->name('chat.room.message.create');
        Route::delete('/room/{id}/message/{message_id}', [RoomMessageController::class, 'delete'])->name('chat.room.message.delete');

        Route::post('/room/{room_id}/report/{message_id}', [RoomMessageReportController::class, 'create'])->name('chat.room.message.report.create');

        Route::get('/friends', [ChatFriendListController::class, 'index'])->name('chat.friends.index');
    });

    Route::prefix('u')->group(function () {
        Route::get('/{username}', [UserProfileController::class, 'index'])->name('user.index');

        Route::prefix('/{id}/report')->group(function () {
            Route::post('/', [ReportUserController::class, 'create'])->name('user.report.create');
        });

        Route::prefix('/{username}/comments')->group(function () {
            Route::post('/', [UserProfileCommentController::class, 'create'])->name('user.comments.create');
            Route::get('/', [UserProfileCommentController::class, 'index'])->name('user.comments.index');
            Route::delete('/{id}', [UserProfileCommentController::class, 'destroy'])->name('user.comments.destroy');
        });
    });

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::get('/user', [UserController::class, 'render'])->name('user');

    Route::get('/', [HomeController::class, 'render'])->name('home');
});
