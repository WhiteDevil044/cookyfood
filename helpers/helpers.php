<?php

function app(): \Framework\Application
{
    return \Framework\Application::$app;
}

function request(): \Framework\Request
{
    return app()->request;
}

function response(): \Framework\Response
{
    return app()->response;
}

function session(): \Framework\Session
{
    return app()->session;
}


function get_route_params(): array
{
    return app()->router->route_params;
}

function get_route_param($key, $default = ''): string
{
    return app()->router->route_params[$key] ?? $default;
}

function array_value_search($arr, $index, $value): int|string|null
{
    foreach ($arr as $k => $v) {
        if ($v[$index] == $value) {
            return $k;
        }
    }
    return null;
}

function db(): \Framework\Database
{
    return app()->db;
}

function view($view = '', $data = [], $layout = ''): string|\Framework\View
{
    if ($view) {
        return app()->view->render($view, $data, $layout);
    }
    return app()->view;
}

function abort($error = '', $code = 404)
{
    response()->setResponseCode($code);
    echo view("errors/{$code}", ['error' => $error], false);
    die;
}

function base_url($path = ''): string
{
    return PATH . $path;
}

function base_href($path = ''): string
{
    if (app()->get('lang')['base'] != 1) {
        return PATH . '/' . app()->get('lang')['code'] . $path;
    }
    return PATH . $path;
}

function uri_without_lang(): string
{
    $request_uri = request()->uri;
    $request_uri = explode('/', $request_uri, 2);
    if (array_key_exists($request_uri[0], LANGS)) {
        unset($request_uri[0]);
    }
    $request_uri = implode('/', $request_uri);
    return $request_uri ? '/' . $request_uri : '';
}

function get_alerts(): void
{
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $k => $v) {
            echo view()->renderPartial("incs/alert_{$k}", ["flash_{$k}" => session()->getFlash($k)]);
        }
    }
}

function get_errors($fieldname): string
{
    $output = '';
    $errors = session()->get('form_errors');
    if (isset($errors[$fieldname])) {
        $output .= '<div class="invalid-feedback d-block"><ul class="list-unstyled">';
        foreach ($errors[$fieldname] as $error) {
            $output .= "<li>$error</li>";
        }
        $output .= '</ul></div>';
    }
    return $output;
}

function get_validation_class($fieldname): string
{
    $errors = session()->get('form_errors');
    if (empty($errors)) {
        return '';
    }
    return isset($errors[$fieldname]) ? 'is-invalid' : 'is-valid';
}

function old($fieldname): string
{
    return isset(session()->get('form_data')[$fieldname]) ? h(session()->get('form_data')[$fieldname]) : '';
}

function h($str): string
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function get_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . session()->get('csrf_token') . '">';
}

function get_csrf_meta(): string
{
    return '<meta name="csrf-token" content="' . session()->get('csrf_token') . '">';
}

function check_auth(): bool
{
    return \Framework\Auth::isAuth();
}

function get_user()
{
    return \Framework\Auth::user();
}

function logout()
{
    \Framework\Auth::logout();
}

function _e($key): void
{
    echo \Framework\Language::get($key);
}

function __($key): string
{
    return \Framework\Language::get($key);
}

function translite(string $text): string
{
    $text  = strtr(
        $text,
        [
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => '',
            'ы' => 'y',
            'ъ' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            ' ' => '-',
            'А' => 'a',
            'Б' => 'b',
            'В' => 'v',
            'Г' => 'g',
            'Д' => 'd',
            'Е' => 'e',
            'Ё' => 'e',
            'Ж' => 'zh',
            'З' => 'z',
            'И' => 'i',
            'Й' => 'y',
            'К' => 'k',
            'Л' => 'l',
            'М' => 'm',
            'Н' => 'n',
            'О' => 'o',
            'П' => 'p',
            'Р' => 'r',
            'С' => 's',
            'Т' => 't',
            'У' => 'u',
            'Ф' => 'f',
            'Х' => 'h',
            'Ц' => 'c',
            'Ч' => 'ch',
            'Ш' => 'sh',
            'Щ' => 'sch',
            'Ь' => '',
            'Ы' => 'y',
            'Ъ' => '',
            'Э' => 'e',
            'Ю' => 'yu',
            'Я' => 'ya'
        ]
    );
    return mb_strtolower($text);
}
