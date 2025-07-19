<?php

namespace App\Controllers;

use Framework\Controller;

class SelectionController extends Controller
{

    public function index()
    {
        $categories = db()->query('SELECT * FROM category_translations WHERE locale=?', [app()->get('lang')['id'] == 1 ? 'ru' : 'en'])->get();

        return view(
            'selections/index',
            [
                'categories' => $categories
            ]
        );
    }

    public function findCategory()
    {
        $slug = app()->router->route_params['slug'];
        $locale = app()->get('lang')['id'] == 1 ? 'ru' : 'en';



        $allCategories = db()->query('SELECT * FROM category_translations WHERE locale=?', [$locale])->get();

        $categoryID = null;
        $categoryName = null;

        foreach ($allCategories as $cat) {
            $categorySlug = mb_strtolower(translite($cat['name']));
            if ($categorySlug === mb_strtolower($slug)) {
                $categoryID = $cat['category_id'];
                $categoryName = $cat['name'];
                break;
            }
        }

        $query = 'SELECT recipes.*, recipe_translations.* 
              FROM recipes 
              JOIN recipe_translations ON recipes.recipe_id = recipe_translations.recipe_id
              WHERE recipes.category_id = ? AND recipe_translations.locale = ?';

        $recipes = db()->query($query, [$categoryID, $locale])->get();


        return view('selection/index', [
            'categoryName' => $categoryName,
            'recipes' => $recipes,
        ]);
    }
}
