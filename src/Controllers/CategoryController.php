<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use PDO;

class CategoryController
{
    public function show(int $categoryId): void
    {
        $databaseConnection = Database::getInstance();

        $categoryQuery = $databaseConnection->prepare("SELECT name, description FROM categories WHERE id = :id");
        $categoryQuery->execute(['id' => $categoryId]);
        $category = $categoryQuery->fetch();

        if (!$category) {
            header("HTTP/1.0 404 Not Found"); 
            View::render('404.tpl', [
                'title'   => '404 Category Not Found',
            ]);
            exit;
        }

        $allowedSorts = [
            'date' => 'p.created_at DESC, p.id DESC',
            'views' => 'p.views DESC, p.id DESC'
        ];

        $currentSort = isset($_GET['sort']) && isset($allowedSorts[$_GET['sort']]) ? $_GET['sort'] : 'date';
        $orderByString = $allowedSorts[$currentSort];

        $postsPerPage = 3;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $offset = ($currentPage - 1) * $postsPerPage;

        $countQuery = $databaseConnection->prepare("SELECT COUNT(*) FROM post_category WHERE category_id = :category_id");

        $countQuery->execute(['category_id' => $categoryId]);
        $totalPosts = (int)$countQuery->fetchColumn();
        $totalPages = (int)ceil($totalPosts / $postsPerPage);

        $postsQuery = $databaseConnection->prepare(" SELECT p.id, p.title, p.description, p.image, p.views, p.created_at 
            FROM posts p INNER JOIN post_category pc ON p.id = pc.post_id WHERE pc.category_id = :category_id
            ORDER BY {$orderByString} LIMIT :limit OFFSET :offset"
        );

        $postsQuery->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $postsQuery->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
        $postsQuery->bindValue(':offset', $offset, PDO::PARAM_INT);
        $postsQuery->execute();

        $posts = $postsQuery->fetchAll();

        View::render('category.tpl', [
            'category' => [
                'id' => $categoryId,
                'name' => $category['name'],
                'description' => $category['description'],
            ], 'posts' => $posts, 'currentPage' => $currentPage, 'totalPages' => $totalPages, 'currentSort' => $currentSort
        ]);
    }
}