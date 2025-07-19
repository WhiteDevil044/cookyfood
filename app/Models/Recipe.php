<?php

namespace App\Models;

use Framework\Model;
use Framework\Application;
use Framework\File;

class Recipe extends Model
{
    public function createRecipe()
    {
        $data = request()->getData();
        $user_id = session()->get("user")['id'];

        $imagePath = '';
        $file = new File('image');

        if ($file->isFile) {

            if ($file->getSize() > 5 * 1024 * 1024) {
                abort('Слишком большой файл', 500);
            }

            $imagePath = $file->save('');

            if (!$imagePath) {
                abort('Файл не дошел', 500);
            }
        }

        db()->beginTransaction();

        try {
            $recipeQuery = "
                INSERT INTO recipes (user_id, created_at, updated_at, category_id, image) 
                VALUES (:user_id, NOW(), NOW(), :category_id, :image)
            ";

            $recipeParams = [
                ':user_id' => $user_id,
                ':category_id' => $data['category_id'],
                ':image' => $imagePath
            ];

            db()->query($recipeQuery, $recipeParams);

            $rep_id = db()->lastInsertId();

            if (!$rep_id || $rep_id == 0) {
                abort('что пошло не так', 500);
            }

            $translationQuery = "
                INSERT INTO recipe_translations (
                    recipe_id, 
                    locale, 
                    title, 
                    description, 
                    ingredients, 
                    instructions, 
                    time_cooking
                ) VALUES (
                    :recipe_id,
                    :locale,
                    :title,
                    :description,
                    :ingredients,
                    :instructions,
                    :time_cooking
                )
            ";

            $ingredientsJson = json_encode($data['ingredients'], JSON_UNESCAPED_UNICODE);
            $instructionsJson = json_encode($data['instructions'], JSON_UNESCAPED_UNICODE);

            $locale = app()->get('lang')['id'] == 1 ? 'ru' : 'en';

            $translationParams = [
                ':recipe_id'    => $rep_id,
                ':locale'       => $locale,
                ':title'        => $data['title'],
                ':description'  => $data['description'],
                ':ingredients'  => $ingredientsJson,
                ':instructions' => $instructionsJson,
                ':time_cooking' => $data['cooking_time']
            ];

            db()->query($translationQuery, $translationParams);

            db()->commit();

            return $rep_id;
        } catch (\Exception $e) {
            db()->rollBack();

            if ($imagePath && file_exists(WWW . $imagePath)) {
                @unlink(WWW . $imagePath);
            }

            abort('Не получилось создать рецепт', 500);
        }
    }

    public function updateRecipe($id)
    {
        $data = request()->getData();
        $imagePath = self::processImageUpload();

        db()->beginTransaction();

        try {
            $recipeSetClause = "category_id = :category_id, updated_at = NOW()";
            $recipeParams = [
                ':category_id' => $data['category_id'],
                ':recipe_id' => $id
            ];

            if ($imagePath) {
                $recipeSetClause .= ", image = :image";
                $recipeParams[':image'] = $imagePath;
            }

            $recipeQuery = "
                UPDATE recipes 
                SET $recipeSetClause
                WHERE recipe_id = :recipe_id
            ";
            db()->query($recipeQuery, $recipeParams);

            $translationQuery = "
                UPDATE recipe_translations 
                SET 
                    title = :title,
                    description = :description,
                    ingredients = :ingredients,
                    instructions = :instructions,
                    time_cooking = :time_cooking
                WHERE recipe_id = :recipe_id AND locale = :locale
            ";

            $translationParams = [
                ':recipe_id'    => $id,
                ':locale'       => app()->get('lang')['id'] == 1 ? 'ru' : 'en',
                ':title'        => $data['title'],
                ':description'  => $data['description'],
                ':ingredients'  => json_encode($data['ingredients'], JSON_UNESCAPED_UNICODE),
                ':instructions' => json_encode($data['instructions'], JSON_UNESCAPED_UNICODE),
                ':time_cooking' => $data['cooking_time']
            ];

            db()->query($translationQuery, $translationParams);
            db()->commit();

            return true;
        } catch (\Exception $e) {
            db()->rollBack();
            abort('Не получилось обновить рецепт', 500);
        }
    }

    public function deleteRecipe($id)
    {
        db()->beginTransaction();

        try {
            $query = "DELETE FROM recipe_translations WHERE recipe_id = ?";
            db()->query($query, [$id]);

            $query = "DELETE FROM recipes WHERE recipe_id = ?";
            db()->query($query, [$id]);
            db()->commit();
            return true;
        } catch (\Exception $e) {
            db()->rollBack();
            abort('Не получилось удалить рецепт', 500);
        }
    }

    private function processImageUpload(): string
    {
        $file = new File('image');
        $imagePath = '';
        if ($file->isFile) {
            $imagePath = $file->save('');
        }
        return $imagePath;
    }
}
