<?php

namespace App\Controllers;

use App\Models\Favorite;
use Framework\Auth;

class FavoritesController extends BaseController
{
    public function index()
    {
        if (!Auth::isAuth()) {
            return response()->redirect('/login');
        }

        $favorites = (new Favorite())->getUserFavorites(Auth::user()['id']);
        return view('favorites/index', [
            'recipes' => $favorites['recipes'],
            'articles' => $favorites['articles'],
            'title' => 'Избранное'
        ]);
    }

    public function add()
    {
        if (!Auth::isAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = request()->getData();
        $type = $data['type'] ?? null;
        $id = $data['id'] ?? null;

        if (!$type || !$id) {
            return response()->json(['error' => 'Missing parameters' . "$type" . " and $id"], 400);
        }

        if (!in_array($type, ['recipe', 'article'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        try {
            $result = (new Favorite())->addFavorite(Auth::user()['id'], $type, (int)$id);

            if ($result) {
                return response()->redirect("/$type/$id");
            }

            return response()->json(['error' => 'Database error'], 500);
        } catch (\Exception $e) {
            abort('Не получилось добавить вашу запись', 500);
        }
    }

    public function remove()
    {
        if (!Auth::isAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = request()->getData();
        $type = $data['type'] ?? null;
        $id = $data['id'] ?? null;

        if (!$type || !$id) {
            return response()->json(['error' =>  "$type || !$id"], 400);
        }

        if (!in_array($type, ['recipe', 'article'])) {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        try {
            $result = (new Favorite())->removeFavorite(Auth::user()['id'], $type, (int)$id);

            if ($result) {
                return response()->redirect("/$type/$id");
            }

            return response()->json(['error' => 'Database error'], 500);
        } catch (\Exception $e) {
            abort('Не получилось удалить вашу запись', 500);
        }
    }
}
