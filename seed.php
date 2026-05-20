<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Database;

$databaseConnection = Database::getInstance();

echo "Seeding started...\n";

try {
    $databaseConnection->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $databaseConnection->exec("DROP TABLE IF EXISTS post_category;");
    $databaseConnection->exec("DROP TABLE IF EXISTS posts;");
    $databaseConnection->exec("DROP TABLE IF EXISTS categories;");
    $databaseConnection->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "The Old tables is dropped...\n";

    $databaseConnection->exec("CREATE TABLE categories (id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL, description TEXT NULL) 
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    );

    $databaseConnection->exec("CREATE TABLE posts ( id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL, description TEXT NOT NULL,
        text TEXT NOT NULL, image VARCHAR(255) NULL,
        views INT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ) 
        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    );

    $databaseConnection->exec("CREATE TABLE post_category (
        post_id INT NOT NULL, category_id INT NOT NULL,
        PRIMARY KEY (post_id, category_id), FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        );

    $categories = [
        ['name' => 'Category 1', 'description' => 'Description for Category 1.'],
        ['name' => 'Category 2', 'description' => 'Description for Category 2.'],
        ['name' => 'Category 3', 'description' => 'Description for Category 3.'],
        ['name' => 'Category 4', 'description' => 'Description for Category 4.'],
        ['name' => 'Category 5', 'description' => 'Description for Category 5.']
    ];

    $insertCategory = $databaseConnection->prepare(
        "INSERT INTO categories (name, description) VALUES (:name, :description)"
    );
    
    foreach ($categories as $category) {
        $insertCategory->execute($category);
    }
    echo "Categories seeded...\n";


    $insertPost = $databaseConnection->prepare(
        "INSERT INTO posts (title, description, text, image, views, created_at) VALUES (:title, :description, :text, :image, :views, :created_at)"
    );

    $insertPivot = $databaseConnection->prepare(
        "INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)"
    );



    for ($i = 1; $i <= 15; $i++) {
        $offsetDays = rand(0, 10);
        $date = date('Y-m-d H:i:s', strtotime("-$offsetDays days"));
        $image = "https://picsum.photos/" . rand(400, 405) . "/300";

        $insertPost->execute([
            'title' => "Test post N {$i}",
            'description' => "Short description for post N {$i}",
            'text' => "Full text of post N {$i} " . str_repeat("Lorem ipsum dolor sit amet. ", 15),
            'image' => $image,
            'views' => rand(10, 850),
            'created_at' => $date
        ]);

        $currentPostId = $databaseConnection->lastInsertId();

        echo "Post N {$i} created, ID - {$currentPostId}\n";

        $randomCategoryIds = array_rand([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5], rand(1, 2));
        if (!is_array($randomCategoryIds)) {
            $randomCategoryIds = [$randomCategoryIds];
        }

        $linkedCategories = [];
        foreach ($randomCategoryIds as $categoryId) {
            $insertPivot->execute([
                'post_id' => $currentPostId, 
                'category_id' => $categoryId
            ]);
            $linkedCategories[] = $categoryId;
        }

        echo "Linked with categories: " . implode(', ', $linkedCategories) . "\n";
    }

    echo "Posts seeded...\n";
    echo "Seeding completed...\n";

} catch (\Exception $exception) {
    die("Critical seedeng error - " . $exception->getMessage() . "\n");
}