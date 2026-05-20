<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($requestUri) {
    case '/':
        $controller = new \App\Controllers\HomeController();
        $controller->index();
        break;

    case '/post':
        $postId = $_GET['id'] ?? null;
        if (!$postId) {
            header("Location: /");
            exit;
        }
        $controller = new \App\Controllers\PostController();
        $controller->show((int)$postId);
        break;

    case '/category':
        $categoryId = $_GET['id'] ?? null;
        if (!$categoryId) {
            header("Location: /");
            exit;
        }
        $controller = new \App\Controllers\CategoryController();
        $controller->show((int)$categoryId);
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        \App\Core\View::render('404.tpl', [
            'title' => '404 Page Not Found'
        ]);
        break;
}