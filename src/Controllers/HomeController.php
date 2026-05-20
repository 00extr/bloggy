<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;

class HomeController
{
    public function index(): void
    {
        $databaseConnection = Database::getInstance();

        $query = " SELECT c.id AS category_id, c.name AS category_name, p.id AS post_id, p.title AS post_title,
            p.description AS post_description, p.image AS post_image, p.views AS post_views, p.created_at AS post_created_at
            FROM categories c INNER JOIN post_category pc ON c.id = pc.category_id
            INNER JOIN ( SELECT *,  ROW_NUMBER() OVER (PARTITION BY pc2.category_id ORDER BY p2.created_at DESC, p2.id DESC) as row_num
            FROM posts p2 INNER JOIN post_category pc2 ON p2.id = pc2.post_id ) p ON p.post_id = pc.post_id AND p.category_id = c.id
            WHERE p.row_num <= 3 ORDER BY c.id ASC, p.created_at DESC";

        $result = $databaseConnection->query($query);
        $rawResults = $result->fetchAll();

        $content = [];
        foreach ($rawResults as $row) {
            $categoryId = $row['category_id'];
            
            if (!isset($content[$categoryId])) {
                $content[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $row['category_name'],
                    'posts' => []
                ];
            }

            $content[$categoryId]['posts'][] = [
                'id' => $row['post_id'],
                'title' => $row['post_title'],
                'description' => $row['post_description'],
                'image' => $row['post_image'],
                'views' => $row['post_views'],
                'created_at' => $row['post_created_at']
            ];
        }

        View::render('home.tpl', [
            'categories' => $content,
            'title' => 'Home',
        ]);
    }
}