<?php

namespace App\Controllers;

use Framework\Pagination;
use App\Models\Recipe;
use App\Models\Selection;
use App\Models\Favorite;
use Framework\Auth;

class RecipesController extends BaseController
{

    public function index()
    {
        $search = trim($_GET['search'] ?? '');
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';
        $params = [$lang];
        $where = 'WHERE rt.locale = ?';
        if ($search !== '') {
            $where .= " AND (rt.title LIKE ? OR rt.description LIKE ?)";
            array_push($params, "%{$search}%", "%{$search}%");
        }

        $pagination_cnt = db()->query("
            SELECT COUNT(*) 
            FROM recipes r 
            JOIN recipe_translations rt ON r.recipe_id = rt.recipe_id 
            {$where}
        ", $params)->getColumn();

        $limit = PAGINATION_SETTINGS['perPage'];
        $pagination = new Pagination($pagination_cnt, $limit, tpl: 'pagination/base2', midSize: 3);

        $query = "
            SELECT r.*, rt.* 
            FROM recipes r 
            JOIN recipe_translations rt ON r.recipe_id = rt.recipe_id 
            {$where}
            LIMIT {$pagination->getOffset()}, {$limit}
        ";
        $recipes = db()->query($query, $params)->get();

        return view('recipes/index', [
            'title' => 'Рецепты',
            'recipes' => $recipes,
            'pagination' => $pagination,
            'search' => $search
        ]);
    }

    public function view()
    {
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';
        $id = app()->router->route_params['id'];
        $params = [$id, $lang];

        $query = "
        SELECT r.*, rt.* 
        FROM recipes r 
        JOIN recipe_translations rt ON r.recipe_id = rt.recipe_id 
        WHERE r.recipe_id = ? AND rt.locale = ?
    ";
        $recipe = db()->query($query, $params)->getOne();

        if (!$recipe) {
            return view('errors/404');
        }

        $isFavorite = false;
        if (Auth::isAuth()) {
            $favoriteModel = new Favorite();
            $isFavorite = $favoriteModel->isFavorite(
                Auth::user()['id'],
                'recipe',
                $recipe['recipe_id']
            );
        }

        return view('recipe/index', [
            'recipe' => $recipe,
            'isFavorite' => $isFavorite
        ]);
    }



    public function update()
    {
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';
        $id = app()->router->route_params['id'];
        $params = [$id, $lang];

        $query = "
        SELECT r.*, rt.* 
        FROM recipes r 
        JOIN recipe_translations rt ON r.recipe_id = rt.recipe_id 
        WHERE r.recipe_id = ? AND rt.locale = ?
    ";
        $recipe = db()->query($query, $params)->get();

        $categories = (new Selection)->getCategories($lang);

        return view('recipe/edit/index', [
            'recipe' => $recipe[0] ?? null,
            'categories' => $categories,
        ]);
    }

    public function edit()
    {
        $id = app()->router->route_params['id'];

        try {
            (new Recipe)->updateRecipe($id);
            response()->redirect(base_href("/recipe/$id"));
        } catch (\Exception $e) {
            abort('Не получилось обновить рецепт', 500);
        }
    }

    public function store()
    {
        $categories = new Selection;
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';
        $categories = $categories->getCategories($lang);
        return view(
            'recipe/create/index',
            [
                "categories" => $categories

            ]
        );
    }
    public function create()
    {
        $recipe = new Recipe;
        $id = $recipe->createRecipe() ?? 1;

        response()->redirect(base_href("/recipe/$id"));
    }
    public function destroy()
    {
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';
        $id = app()->router->route_params['id'];
        $params = [$id, $lang];

        $query = "
            SELECT r.*, rt.* 
            FROM recipes r 
            JOIN recipe_translations rt ON r.recipe_id = rt.recipe_id 
            WHERE r.recipe_id = ? AND rt.locale = ?
        ";
        $recipe = db()->query($query, $params)->get();

        return view('recipe/delete/index', [
            'recipe' => $recipe[0] ?? null
        ]);
    }

    public function delete()
    {
        $id = app()->router->route_params['id'];



        try {
            $recipeModel = new Recipe();
            $recipeModel->deleteRecipe($id);
            response()->redirect('/recipes')->withSuccess('Рецепт успешно удален');
        } catch (\Exception $e) {
            abort('Не получилось удалить рецепт', 500);
        }
    }
}
