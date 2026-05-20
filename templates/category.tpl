{include file='header.tpl'}
    <div class="container">
        <a class="back_link" href="/">Back to Home</a>
        
        <h1 class="category_title">{$category.name}</h1>
        <p class="category_description">{$category.description}</p>

        <div class="sort_panel">
            <span>Sort by:</span>
            <a class="sort_link {if $currentSort == 'date'}active{/if}" href="/category?id={$category.id}&sort=date" >Date</a>
            <a class="sort_link {if $currentSort == 'views'}active{/if}" href="/category?id={$category.id}&sort=views">Views</a>
        </div>

        <div class="content_wrapper">
            {foreach $posts as $post}
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
            {foreachelse}
                <p>Articles not found in cateegory</p>
            {/foreach}
        </div>

        {if $totalPages > 1}
            <div class="pagination">
                {if $currentPage > 1}
                    <a class="pagination_link" href="/category?id={$category.id}&page={$currentPage - 1}">&laquo; Prev</a>
                {/if}

                <span class="pagination_text">Page {$currentPage} of {$totalPages}</span>

                {if $currentPage < $totalPages}
                    <a class="pagination_link" href="/category?id={$category.id}&page={$currentPage + 1}">Next &raquo;</a>
                {/if}
            </div>
        {/if}
    </div>
{include file='footer.tpl'}