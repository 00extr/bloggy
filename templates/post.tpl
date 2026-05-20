{include file='header.tpl'}

<div class="container">
    {if $categories}
        <a class="back_link" href="/category?id={$categories[0].id}">Back to {$categories[0].name}</a>
    {else}
        <a class="back_link" href="/">Back to Home</a>
    {/if}
    <article class="post_container">
        <h1 class="post_title">{$post.title}</h1>
        
        <div class="content_meta">
            <span>Published: {$post.created_at}</span> | 
            <span>Views: {$post.views}</span> | 
            {foreach $categories as $cat}
                <a href="/category?id={$cat.id}">{$cat.name}</a></strong>{if !$cat@last}, {/if}
            {/foreach}
        </div>

        {if $post.image}
            <img class="post_main_image" src="{$post.image}" alt="{$post.title}">
        {/if}

        <div class="post_text">
            {$post.text|nl2br}
        </div>
    </article>

    <div class="related_section">
        <h2 class="related_title">Similar Articles</h2>
        <div class="related_wrapper">
            {foreach $similarPosts as $similar}
                <div class="related_card">
                    {if $similar.image}
                        <a href="/post?id={$similar.id}">
                            <img class="card_image" src="{$similar.image}" alt="{$similar.title}">
                        </a>
                    {/if}
                    <h4 class="related_card_title">
                        <a class="content_link" href="/post?id={$similar.id}">{$similar.title}</a>
                    </h4>
                    <p class="content_description">{$similar.description}</p>
                </div>
            {foreachelse}
                <p>No similar articles found.</p>
            {/foreach}
        </div>
    </div>
</div>

{include file='footer.tpl'}