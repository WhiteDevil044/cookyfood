<?php

namespace App\Controllers;

use App\Models\User;
use Framework\Auth;
use Framework\Pagination;

class UserController extends BaseController
{

    public function register()
    {

        return view('user/register', [
            'title' => 'Register page',
        ]);
    }

    public function store()
    {
        $model = new User();
        $model->loadData();

        $rawPassword = $model->attributes['password'];
        $model->attributes['password'] = password_hash($rawPassword, PASSWORD_DEFAULT);

        if (request()->isAjax()) {
            if (!$model->validate([], 'register')) {
                echo json_encode(['status' => 'error', 'data' => $model->listErrors()]);
                die;
            }

            if ($id = $model->save()) {
                echo json_encode(['status' => 'success', 'data' => sprintf(__('user_store_success'), $id), 'redirect' => base_href('/login')]);
            } else {
                echo json_encode(['status' => 'error', 'data' => 'Error registration']);
            }
            die;
        }

        if (!$model->validate([], 'register')) {
            session()->setFlash('error', 'Validation errors');
            session()->set('form_errors', $model->getErrors());
            session()->set('form_data', $model->attributes);
        } else {
            if ($id = $model->save()) {
                session()->setFlash('success', 'Thanks for registration. Your ID: ' . $id);
            } else {
                session()->setFlash('error', 'Error registration');
            }
        }
        response()->redirect('/register');
    }

    public function login()
    {

        return view('user/login', [
            'title' => 'Login page',
        ]);
    }

    public function auth()
    {
        $model = new User();
        $model->loadData();

        if (!$model->validate([], 'login')) {
            session()->setFlash('error', 'Validation errors');
            session()->set('form_errors', $model->getErrors());
            session()->set('form_data', $model->attributes);
        }

        if (Auth::login([
            'email' => $model->attributes['email'],
            'password' => $model->attributes['password'],
        ])) {
            echo json_encode(['status' => 'success', 'data' => 'Success login', 'redirect' => base_href('/recipes')]);
        } else {
            echo json_encode(['status' => 'error', 'data' => 'Wrong email or password']);
        }
        response()->redirect(base_href('/login'));
    }

    public function logout()
    {
        Auth::logout();
        response()->redirect(base_href('/login'));
    }

    public function index()
    {
        $users_cnt = db()->query("select count(*) from users")->getColumn();
        $limit = PAGINATION_SETTINGS['perPage'];

        $pagination = new Pagination($users_cnt, $limit, tpl: 'pagination/base2', midSize: 3);

        $users = db()->query("select * from users limit $limit offset {$pagination->getOffset()}")->get();


        return view('user/index', [
            'title' => 'Users',
            'users' => $users,
            'pagination' => $pagination,
        ]);
    }
    public function calc()
    {
        return view('calculator/index', [
            'title' => 'calculator'
        ]);
    }
}
