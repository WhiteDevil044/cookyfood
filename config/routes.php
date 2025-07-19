<?php

/** @var \Framework\Application $app */

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\PostController;
use App\Controllers\RecipesController;
use App\Controllers\SelectionController;
use App\Controllers\ArticleController;
use App\Controllers\FavoritesController;


const MIDDLEWARE = [
    'auth' => \Framework\Middleware\Auth::class,
    'guest' => \Framework\Middleware\Guest::class,
];

$app->router->add('/api/v1/test', function () {
    response()->json(['status' => 'ok', 'message' => 'Success page']);
}, ['get', 'post', 'put'])->withoutCsrfToken();

$app->router->get('/api/v1/recipes', [\App\Controllers\Api\V1\RecipeControllerApi::class, 'index'])->withoutCsrfToken();
$app->router->get('/api/v1/recipe/(?P<slug>[a-z0-9-]+)', [\App\Controllers\Api\V1\RecipeControllerApi::class, 'view']);

$app->router->get('/favorites', [FavoritesController::class, 'index'])->middleware(['auth']);


$app->router->get('/register', [UserController::class, 'register'])->middleware(['guest']);
$app->router->post('/register', [UserController::class, 'store'])->middleware(['guest']);
$app->router->get('/logout', [UserController::class, 'logout'])->middleware(['auth']);
$app->router->post('/login', [UserController::class, 'auth'])->middleware(['guest']);
$app->router->get('/login', [UserController::class, 'login'])->middleware(['guest']);
$app->router->get('/calculator', [UserController::class, 'calc']);

$app->router->post('/favorite/add', [FavoritesController::class, 'add']);
$app->router->post('/favorite/remove', [FavoritesController::class, 'remove']);
$app->router->get('/favorites', [FavoritesController::class, 'index'])->middleware(['auth']);

$app->router->post('/recipe/edit/(?P<id>[a-z0-9-]+)', [RecipesController::class, 'edit']);
$app->router->post('/recipe/create', [RecipesController::class, 'create']);
$app->router->post('/recipe/delete/(?P<id>[a-z0-9-]+)', [RecipesController::class, 'delete']);
$app->router->get('/recipe/create', [RecipesController::class, 'store']);
$app->router->get('/recipe/(?P<id>[a-z0-9-]+)', [RecipesController::class, 'view']);
$app->router->get('/recipe/edit/(?P<id>[a-z0-9-]+)', [RecipesController::class, 'update']);
$app->router->get('/recipe/delete/(?P<id>[a-z0-9-]+)', [RecipesController::class, 'destroy']);
$app->router->get('/recipes', [RecipesController::class, 'index']);

$app->router->post('/article/edit/(?P<id>[a-z0-9-]+)', [ArticleController::class, 'edit']);
$app->router->post('/article/create', [ArticleController::class, 'create']);
$app->router->get('/article/delete/(?P<id>[a-z0-9-]+)', [ArticleController::class, 'delete']);
$app->router->get('/article/create', [ArticleController::class, 'store']);
$app->router->get('/article/edit/(?P<id>[a-z0-9-]+)', [ArticleController::class, 'update']);
$app->router->get('/article/(?P<id>[a-z0-9-]+)', [ArticleController::class, 'view']);
$app->router->get('/articles', [ArticleController::class, 'index']);

$app->router->get('/selection/(?P<slug>[a-z0-9-]+)', [SelectionController::class, 'findCategory']);
$app->router->get('/selections', [SelectionController::class, 'index']);


$app->router->get('/', [HomeController::class, 'index']);

// dump(__FILE__ . __LINE__, $app->router->getRoutes());
