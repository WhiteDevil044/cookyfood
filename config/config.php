<?php

define("ROOT", dirname(__DIR__));
const DEBUG = 0;
const WWW = ROOT . '/public';
const CONFIG = ROOT . '/config';
const HELPERS = ROOT . '/helpers';
const APP = ROOT . '/app';
const CORE = ROOT . '/core';
const VIEWS = APP . '/Views';
const ERROR_LOGS = ROOT . '/tmp/error.log';
const LAYOUT = 'default';
const UPLOADS = WWW . '/assets/img';
const PATH = 'https://cookyfood';

const DB_SETTINGS = [
    'driver' => 'mysql',
    'host' => 'MySQL-8.4',
    'database' => 'database',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'port' => 3306,
    'prefix' => '',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];



const PAGINATION_SETTINGS = [
    'perPage' => 6,
    'midSize' => 2,
    'maxPages' => 7,
    'tpl' => 'pagination/base',
];

const MULTILANGS = 1;

const LANGS = [
    'ru' => [
        'id' => 1,
        'code' => 'ru',
        'title' => 'Русский',
        'base' => 1,
    ],
    'en' => [
        'id' => 2,
        'code' => 'en',
        'title' => 'English',
        'base' => 0,
    ],

];
