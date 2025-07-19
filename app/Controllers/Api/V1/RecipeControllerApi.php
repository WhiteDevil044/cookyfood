<?php

namespace App\Controllers\Api\V1;

class RecipeControllerApi
{

    public function index()
    {
        $recipes = db()->findAll('recipes');
        response()->json(['status' => 'ok', 'data' => $recipes]);
    }

    public function view()
    {
        $slug = app()->router->route_params['slug'];
        $recipe = db()->findOrFail('recipes', $slug, 'recipe_id');
        response()->json(['status' => 'ok', 'data' => $recipe]);
    }

}