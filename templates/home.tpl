<body>
    <style>
        {literal}
            .category_block {
                background: #fff;
                padding: 25px;
                margin-bottom: 40px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
            .block_title {
                color: #2c3e50;
                border-bottom: 2px solid #3498db;
                padding-bottom: 10px;
                margin-top: 0;
            }

            .content_wrapper {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
                margin-top: 20px;
                align-items: flex-start; 
            }

            .content {
                box-sizing: border-box; 
                width: calc((100% - 40px) / 3); 
                min-width: 240px; 
                background: #fafafa;
                padding: 15px;
                border-radius: 6px;
                border: 1px solid #eee;
            }

            .content_title {
                margin-top: 0;
            }

            .content_meta {
                color: #777;
                font-size: 12px;
                margin-bottom: 10px;
            }

            .content_image {
                max-width: 100%;
                min-width: 100%;
                height: auto;
                border-radius: 4px;
                margin: 10px 0;
            }

            .content_description {
                font-size: 14px;
                line-height: 1.4;
            }

            .category_link-wrapper {
                margin-top: 20px;
                text-align: right;
            }

            .category_link {
                display: inline-block;
                background: #3498db;
                color: #fff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                font-size: 14px;
            }


        {/literal}
</style>
    <div class="container">
        <h1>Bloggy</h1>

        {foreach $categories as $category}
            <div class="category_block">
                <h2 class="block_title">
                    {$category.name}
                </h2>
                
                <div class="content_wrapper">
                    {foreach $category.posts as $post}
                        <div class="content">
                            {if $post.image}
                                <img class="content_image" src="{$post.image}" alt="{$post.title}">
                            {/if}
                            <h3 class="content_title">
                                <a class="content_link" href="/post?id={$post.id}">{$post.title}</a>
                            </h3>
                            <div class="conent_meta">
                                Date: {$post.created_at} | Views: {$post.views}
                            </div>
                            <p class="content_description">{$post.description}</p>
                        </div>
                    {/foreach}
                </div>

                <div class="category_link-wrapper">
                    <a class="category_link" href="/category?id={$category.id}">All Articles</a>
                </div>
            </div>
        {/foreach}

    </div>
</body>