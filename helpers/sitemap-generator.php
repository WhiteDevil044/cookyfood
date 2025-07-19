<?php
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../vendor/autoload.php';

$domain = get_domain();
$dbConfig = require __DIR__ . '/../config/database.php';

// Инициализация подключения к БД
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($dbConfig);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$urls = [
    ['loc' => '/', 'priority' => 1.0],
    ['loc' => '/login', 'priority' => 0.8],
    ['loc' => '/register', 'priority' => 0.8],
    ['loc' => '/recipes', 'priority' => 0.9],
    ['loc' => '/articles', 'priority' => 0.9],
    ['loc' => '/selections', 'priority' => 0.9],
    ['loc' => '/favorites', 'priority' => 0.7]
];

// Категории с переводами
$categories = $capsule->table('category_translations')
    ->select('locale', 'name')
    ->distinct()
    ->join('categories', 'category_translations.category_id', '=', 'categories.category_id')
    ->get();

foreach ($categories as $category) {
    $slug = translite($category->name);
    $urls[] = [
        'loc' => "/{$category->locale}/selection/{$slug}",
        'priority' => 0.85,
        'lastmod' => date('c', strtotime('-1 month')) // Обновляем раз в месяц
    ];
}

// Рецепты
$recipes = $capsule->table('recipes')->get();
foreach ($recipes as $recipe) {
    $urls[] = [
        'loc' => "/recipe/{$recipe->recipe_id}",
        'priority' => 0.9,
        'lastmod' => date('c', strtotime($recipe->updated_at))
    ];
}

// Статьи
$articles = $capsule->table('articles')->get();
foreach ($articles as $article) {
    $urls[] = [
        'loc' => "/article/{$article->article_id}",
        'priority' => 0.9,
        'lastmod' => date('c', strtotime($article->updated_at))
    ];
}

// Генерация XML
$xml = new XMLWriter();
$xml->openMemory();
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement('urlset');
$xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

foreach ($urls as $url) {
    $xml->startElement('url');
    $xml->writeElement('loc', $domain . $url['loc']);
    $xml->writeElement('priority', $url['priority']);
    $xml->writeElement('changefreq', 'monthly');
    
    if (isset($url['lastmod'])) {
        $xml->writeElement('lastmod', $url['lastmod']);
    } else {
        $xml->writeElement('lastmod', date('c'));
    }
    
    $xml->endElement();
}

$xml->endElement();
file_put_contents(__DIR__ . '/../public/sitemap.xml', $xml->outputMemory());

echo "Sitemap generated successfully!\n";