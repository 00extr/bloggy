{include file='header.tpl'}
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
                            <div class="content_meta">
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
{include file='footer.tpl'}