<?php

namespace App\Models;

use Framework\Model;
use Framework\File;
use Exception;
use Framework\Pagination;

class Article extends Model
{
    public function createArticle(): int
    {
        $data = request()->getData();
        $user_id = session()->get("user")['id'] ?? null;

        $imagePath = $this->processImageUpload();

        db()->beginTransaction();

        try {
            $articleQuery = "
                INSERT INTO articles (user_id, created_at, updated_at, image) 
                VALUES (:user_id, NOW(), NOW(), :image)
            ";

            db()->query($articleQuery, [
                ':user_id' => $user_id,
                ':image' => $imagePath
            ]);
            $articleId = db()->lastInsertId();
            if (!$articleId) {
                abort('Ошибка создание статьи', 500);
            }

            $this->createTranslation(
                $articleId,
                $data['title'],
                $data['paragraphs'][0],
                $data['paragraphs']
            );

            db()->commit();
            return $articleId;
        } catch (Exception $e) {
            db()->rollBack();
            $this->deleteUploadedImage($imagePath);
            abort('Не получилось создать статью', 500);
        }
    }

    public function updateArticle(int $id): bool
    {
        $data = request()->getData();
        $imagePath = $this->processImageUpload();

        db()->beginTransaction();

        try {
            $this->updateTranslation(
                $id,
                $data['title'],
                $data['paragraphs']
            );

            $articleSetClause = "updated_at = NOW()";
            $articleParams = [':id' => $id];

            if ($imagePath) {
                $articleSetClause = "image = :image, updated_at = NOW()";
                $articleParams[':image'] = $imagePath;
            }

            db()->query("
                UPDATE articles 
                SET $articleSetClause 
                WHERE article_id = :id
            ", $articleParams);

            db()->commit();
            return true;
        } catch (Exception $e) {
            db()->rollBack();
            $this->deleteUploadedImage($imagePath);
            abort('Не получилось обновить статью', 500);
        }
    }

    public function deleteArticle(int $id): bool
    {
        db()->beginTransaction();

        try {
            $imagePath = db()->query("
                SELECT image 
                FROM articles 
                WHERE article_id = :id
            ", [':id' => $id])->getColumn();

            db()->query("
                DELETE FROM article_translations 
                WHERE article_id = :id
            ", [':id' => $id]);

            db()->query("
                DELETE FROM articles 
                WHERE article_id = :id
            ", [':id' => $id]);

            db()->commit();

            $this->deleteUploadedImage($imagePath);

            return true;
        } catch (Exception $e) {
            db()->rollBack();
            abort('Не получилось удалить рецепт', 500);
        }
    }

    public function getArticles(string $lang, string $search = ''): array
    {
        $params = [$lang];
        $where = 'WHERE at.locale = ?';

        if ($search !== '') {
            $where .= " AND (at.title LIKE ?)";
            $params[] = "%{$search}%";
        }

        $countQuery = "
            SELECT COUNT(*) 
            FROM articles a 
            JOIN article_translations at ON a.article_id = at.article_id 
            {$where}
        ";
        $total = db()->query($countQuery, $params)->getColumn();

        $limit = PAGINATION_SETTINGS['perPage'] ?? 10;
        $pagination = new Pagination($total, $limit, tpl: 'pagination/base2', midSize: 3);

        $query = "
            SELECT a.*, at.* 
            FROM articles a 
            JOIN article_translations at ON a.article_id = at.article_id 
            {$where}
            ORDER BY a.created_at DESC
            LIMIT {$pagination->getOffset()}, {$limit}
        ";

        $articles = db()->query($query, $params)->get();

        foreach ($articles as &$article) {
            $article['paragraphs'] = json_decode($article['paragraphs'], true) ?? [];
        }

        return [
            'articles' => $articles,
            'pagination' => $pagination
        ];
    }

    public function getArticleById(int $id, string $lang): array
    {
        $query = "
            SELECT a.*, at.*, u.name AS author_name
            FROM articles a 
            JOIN article_translations at ON a.article_id = at.article_id 
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.article_id = :id AND at.locale = :lang
        ";

        $article = db()->query($query, [
            ':id' => $id,
            ':lang' => $lang
        ])->getOne();

        if (!$article) {
            throw new Exception("Статья не найдена");
        }

        $article['paragraphs'] = json_decode($article['paragraphs'], true) ?? [];

        return $article;
    }

    private function createTranslation(
        int $articleId,
        string $title,
        string $content,
        array $paragraphs
    ): void {
        $locale = app()->get('lang')['id'] == 1 ? 'ru' : 'en';

        $query = "
            INSERT INTO article_translations (
                article_id, 
                locale, 
                title,
                content, 
                paragraphs
            ) VALUES (
                :article_id,
                :locale,
                :title,
                :content,
                :paragraphs
            )
        ";

        $params = [
            ':article_id' => $articleId,
            ':locale' => $locale,
            ':title' => $title,
            ':content' => $content,
            ':paragraphs' => json_encode($paragraphs, JSON_UNESCAPED_UNICODE)
        ];

        db()->query($query, $params);
    }

    private function updateTranslation(
        int $articleId,
        string $title,
        array $paragraphs
    ): void {
        $locale = app()->get('lang')['id'] == 1 ? 'ru' : 'en';

        $query = "
            UPDATE article_translations 
            SET 
                title = :title,
                paragraphs = :paragraphs
            WHERE article_id = :article_id AND locale = :locale
        ";

        $params = [
            ':article_id' => $articleId,
            ':locale' => $locale,
            ':title' => $title,
            ':paragraphs' => json_encode($paragraphs, JSON_UNESCAPED_UNICODE)
        ];

        db()->query($query, $params);
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

    private function deleteUploadedImage(string $path): void
    {
        if ($path && file_exists(WWW . $path)) {
            @unlink(WWW . $path);
        }
    }
}
