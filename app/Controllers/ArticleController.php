<?php

namespace App\Controllers;

use Framework\Pagination;
use Framework\Auth;
use App\Models\Article;
use App\Models\Favorite;
use Exception;
use DateTime;

class ArticleController extends BaseController
{
    public function index(): string
    {
        $search = trim($_GET['search'] ?? '');
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';

        $articleModel = new Article();
        $result = $articleModel->getArticles($lang, $search);

        return view('articles/index', [
            'title' => 'Статьи',
            'articles' => $result['articles'],
            'pagination' => $result['pagination'],
            'search' => $search
        ]);
    }

    public function view()
    {
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';
        $id = app()->router->route_params['id'];

        $articleModel = new Article();
        $article = $articleModel->getArticleById($id, $lang);


        $date = new DateTime($article['created_at']);
        $months = [
            'ru' => [
                'января',
                'февраля',
                'марта',
                'апреля',
                'мая',
                'июня',
                'июля',
                'августа',
                'сентября',
                'октября',
                'ноября',
                'декабря'
            ],
            'en' => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ]
        ];

        $formattedDate = $lang === 'ru'
            ? $date->format('d') . ' ' . $months['ru'][$date->format('n') - 1] . ' ' . $date->format('Y')
            : $date->format('F d, Y');

        $isFavorite = false;
        if (Auth::isAuth()) {
            $favoriteModel = new Favorite();
            $isFavorite = $favoriteModel->isFavorite(
                Auth::user()['id'],
                'article',
                $article['article_id']
            );
        }

        return view('article/index', [
            'article' => $article,
            'formattedDate' => $formattedDate,
            'isFavorite' => $isFavorite
        ]);
    }

    public function store()
    {
        return view('article/create/index');
    }

    public function create()
    {

        $articleModel = new Article();
        $id = $articleModel->createArticle() ?? 1;
        response()->redirect("/article/{$id}");
    }

    public function update()
    {
        $id = app()->router->route_params['id'];
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';

        $articleModel = new Article();
        $article = $articleModel->getArticleById($id, $lang);

        return view('article/edit/index', [
            'article' => $article
        ]);
    }

    public function edit()
    {
        $id = app()->router->route_params['id'];

        try {
            $articleModel = new Article();
            $articleModel->updateArticle($id);
            response()->redirect("/article/{$id}");
        } catch (Exception $e) {
            abort('Не получилось обновить статью', 500);
        }
    }

    public function destroy()
    {
        $id = app()->router->route_params['id'];
        $lang = app()->get('lang')['id'] == 1 ? 'ru' : 'en';

        $articleModel = new Article();
        $article = $articleModel->getArticleById($id, $lang);

        return view('article/delete/index', [
            'article' => $article
        ]);
    }

    public function delete()
    {
        $id = app()->router->route_params['id'];

        try {
            $articleModel = new Article();
            $articleModel->deleteArticle($id);
            response()->redirect('/articles')->withSuccess('Статья успешно удалена');
        } catch (Exception $e) {
            abort('Не получилось удалить статью', 500);
        }
    }
}
