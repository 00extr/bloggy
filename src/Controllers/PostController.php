<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use PDO;

class PostController
{
    public function show(int $postId): void
    {
        $databaseConnection = Database::getInstance();

        if ($postId <= 0) {
            header("HTTP/1.0 404 Not Found");
            View::render('404.tpl', [
                'title' => '404 Post Not Found',
            ]);
            exit;
        }

        $postQuery = $databaseConnection->prepare("SELECT id, title, description, text, image, views, created_at FROM posts WHERE id = :id");
        $postQuery->execute(['id' => $postId]);
        $post = $postQuery->fetch();

        if (!$post) {
            header("HTTP/1.0 404 Not Found");
            View::render('404.tpl', [
                'title' => '404 Post Not Found',
            ]);
            exit;
        }

        $updateQuery = $databaseConnection->prepare("UPDATE posts SET views = views + 1 WHERE id = :id");

        $updateQuery->execute(['id' => $postId]);
        $post['views'] = (int)$post['views'] + 1;

        $categoriesQuery = $databaseConnection->prepare("SELECT c.id, c.name FROM categories c INNER JOIN post_category pc ON c.id = pc.category_id
            WHERE pc.post_id = :post_id"
        );

        $categoriesQuery->execute(['post_id' => $postId]);
        $postCategories = $categoriesQuery->fetchAll();

        $similarPostsQuery = $databaseConnection->prepare("SELECT DISTINCT p.id, p.title, p.description, p.image, p.views, p.created_at 
        FROM posts p INNER JOIN post_category pc ON p.id = pc.post_id
            WHERE pc.category_id IN ( SELECT category_id FROM post_category WHERE post_id = :current_id)
             AND p.id != :exclude_id ORDER BY p.created_at DESC, p.id DESC LIMIT 3"
        );
        
        $similarPostsQuery->execute([
            'current_id' => $postId,
            'exclude_id' => $postId
        ]);
        $similarPosts = $similarPostsQuery->fetchAll();

        View::render('post.tpl', [
            'title' => $post['title'],
            'post' => $post,
            'categories' => $postCategories,
            'similarPosts' => $similarPosts
        ]);
    }
}