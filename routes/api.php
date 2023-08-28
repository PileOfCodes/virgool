<?php

use App\Http\Controllers\Admin\AllRolesController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DraftController as AdminDraftController;
use App\Http\Controllers\Admin\FeaturePostController;
use App\Http\Controllers\Admin\FeaturePostSearchController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Category\NavbarCategoryController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Comment\replyCommentController;
use App\Http\Controllers\Draft\DraftController;
use App\Http\Controllers\HomePostController;
use App\Http\Controllers\Post\AllUserDraftsController;
use App\Http\Controllers\Post\AllUserPostsController;
use App\Http\Controllers\Post\BookmarkController;
use App\Http\Controllers\Post\FollowingPostController;
use App\Http\Controllers\Post\LikeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\ShowPostController;
use App\Http\Controllers\Post\TrendingPostController;
use App\Http\Controllers\Post\UploadPostImageController;
use App\Http\Controllers\Search\SearchCategoryController;
use App\Http\Controllers\Search\SearchPostController;
use App\Http\Controllers\Search\SearchUserController;
use App\Http\Controllers\UpdateProfileController;
use App\Http\Controllers\User\BookmarkedPostsController;
use App\Http\Controllers\User\FollowController;
use App\Http\Controllers\User\LikedPostsController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PostCategoriesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FeaturePostController as FeaturedPostController;
use App\Http\Controllers\User\UserPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/me', function (Request $request) {return $request->user();});
    Route::patch('/profile',[UpdateProfileController::class, 'update'])->name('profile.update');
    Route::post('/upload-post-image', [UploadPostImageController::class, 'getPostImage'])->name('upload_post_image');
    Route::post('/posts/create', [DraftController::class, 'store'])->name('draft.store');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');
    Route::patch('/drafts/{draft:link}', [DraftController::class, 'update'])->name('draft.update');
    Route::get('/drafts/{draft:link}', [DraftController::class, 'show'])->name('draft.show');
    Route::get('posts/all-posts', [AllUserPostsController::class, 'getAllPosts']);
    Route::get('posts/all-drafts', [AllUserDraftsController::class, 'getAllDrafts']);
    Route::get('posts/{post:slug}/edit', [PostController::class, 'show'])->name('post.show');
    Route::patch('posts/{post:slug}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('posts/{post:slug}', [PostController::class, 'update'])->name('post.update');
    Route::delete('posts/{post:slug}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::delete('drafts/{draft:link}', [DraftController::class, 'destroy'])->name('draft.destroy');
    Route::post('/comments/{post:slug}', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comment.update');
    Route::post('/replies/{post:slug}', [replyCommentController::class, 'store'])->name('reply.store');
    Route::post('/bookmarks/{post:slug}', [BookmarkController::class, 'store'])->name('bookmark.store');
    Route::delete('/bookmarks/{post:slug}', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
    Route::post('/likes/{post:slug}', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/likes/{post:slug}', [LikeController::class, 'destroy'])->name('like.destroy');
    Route::post('/follow/{user:username}', FollowController::class, )->name('follow');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::patch('/notifications/{notification}', [NotificationController::class, 'update'])->name('notifications.update');
    Route::get('/liked-posts', [LikedPostsController::class,'index']);
    Route::get('/bookmarked-posts', [BookmarkedPostsController::class,'index']);
    Route::get('/following-posts', [FollowingPostController::class,'index']);
});
Route::get('feature-posts',[FeaturedPostController::class,'index']);
Route::get('/posts/category/{category:slug}', [PostCategoriesController::class,'index']);
Route::get('/navbar-categories', [NavbarCategoryController::class,'index']);
Route::get('post/{post:slug}', ShowPostController::class)->name('show-post');
Route::get('/user-posts/{user:username}', [UserPostController::class, 'index']);
Route::get('/home', [HomePostController::class, 'index']);
Route::get('/trending-posts', [TrendingPostController::class, 'index']);
Route::get('/search/posts', [SearchPostController::class, 'index']);
Route::get('/search/users', [SearchUserController::class, 'index']);
Route::get('/search/categories', [SearchCategoryController::class, 'index']);
Route::post('/password/reset', [ResetPasswordController::class , 'reset'])->name('password.update');
Route::post('/password/email', [ForgotPasswordController::class , 'sendResetLinkEmail'])->name('password.email');
Route::post('email/resend', [VerificationController::class,'resend'])->name('verification.resend');

Route::prefix('admin')->middleware(['auth:sanctum','has_role:admin|author'])->group(function () {
    Route::get('dashboard',DashboardController::class);
    Route::apiResource('users',UserController::class);
    Route::apiResource('posts',AdminPostController::class);
    Route::apiResource('drafts',AdminDraftController::class);
    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('comments',AdminCommentController::class);
    Route::get('feature-posts-search',[FeaturePostSearchController::class,'index']);
    Route::get('feature-post',[FeaturePostController::class,'index']);
    Route::post('feature-post/{post:slug}',[FeaturePostController::class,'store']);
    Route::delete('feature-post/{post:slug}',[FeaturePostController::class,'destroy']);
    Route::apiResource('roles', RoleController::class);
    Route::get('all-roles', [AllRolesController::class, 'index']);
});

