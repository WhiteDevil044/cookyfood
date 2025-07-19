<?php

namespace App\Models;

use Framework\Model;

class Favorite extends Model
{
    public function addFavorite(int $userId, string $type, int $itemId): bool
    {
        $field = $type . '_id';
        $query = "INSERT INTO user_favorites (user_id, $field) VALUES (?, ?)";
        return (bool) db()->query($query, [$userId, $itemId]);
    }

    public function removeFavorite(int $userId, string $type, int $itemId): bool
    {
        $field = $type . '_id';
        $query = "DELETE FROM user_favorites WHERE user_id = ? AND $field = ?";
        return (bool) db()->query($query, [$userId, $itemId]);
    }

    public function getUserFavorites(int $userId): array
    {
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';

        $recipes = db()->query("
        SELECT r.*, rt.* 
        FROM user_favorites uf
        LEFT JOIN recipes r ON uf.recipe_id = r.recipe_id
        LEFT JOIN recipe_translations rt ON r.recipe_id = rt.recipe_id 
        WHERE uf.user_id = ? AND rt.locale = ?
    ", [$userId, $lang])->get() ?? [];

        $articles = db()->query("
        SELECT a.*, at.* 
        FROM user_favorites uf
        LEFT JOIN articles a ON uf.article_id = a.article_id
        LEFT JOIN article_translations at ON a.article_id = at.article_id 
        WHERE uf.user_id = ? AND at.locale = ?
    ", [$userId, $lang])->get() ?? [];

        return [
            'recipes' => $recipes,
            'articles' => $articles
        ];
    }

    public function isFavorite(int $userId, string $type, int $itemId): bool
    {
        $field = $type . '_id';
        $result = db()->query("
            SELECT id FROM user_favorites 
            WHERE user_id = ? AND $field = ?
        ", [$userId, $itemId])->getOne();

        return (bool) $result;
    }
}
