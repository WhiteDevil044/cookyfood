<div class="container">
    <h1 align="center"><?php _e('favorites_index_title'); ?></h1>

    <div class="create-actions">
        <a href="<?= base_href('/recipe/create') ?>" class="action-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" class="icon">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            <?php _e('favorites_index_btn_create_recipe'); ?>
        </a>
        <a href="<?= base_href('/article/create') ?>" class="action-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16" class="icon">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            <?php _e('favorites_index_btn_create_article'); ?>
        </a>
    </div>

    <h2><?php _e('favorites_index_recipes'); ?></h2>
    
    <?php if (!empty($recipes)): ?>
        <div class="recipe-grid">
            <?php foreach ($recipes as $recipe): ?>
                <article class="recipe-card">
                    <div class="recipe-image-container">
                        <a href="<?= base_href('/recipe/' . $recipe['recipe_id']) ?>" style="text-decoration: none;">
                            <img src="<?= htmlspecialchars($recipe['image']) ?>"
                                alt="<?= htmlspecialchars($recipe['title']) ?>"
                                class="recipe-image"
                                loading="lazy"
                                onerror="this.src='assets/img/no-image.png'"></a>
                        <div class="recipe-tags">
                            <span class="recipe-badge"><?php _e('favorites_index_badge_recipe'); ?></span>
                            <span class="cooking-time">⏱ <?= $recipe['time_cooking'] ?> <?php _e('favorites_index_min'); ?></span>
                        </div>
                    </div>
                    <div class="recipe-content">
                        <h2 class="recipe-title">
                            <a href="<?= base_href('/recipe/' . $recipe['recipe_id']) ?>" style="text-decoration: none;">
                                <?= htmlspecialchars($recipe['title']) ?>
                            </a>
                        </h2>
                        <p class="recipe-description">
                            <?= htmlspecialchars($recipe['description']) ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p><?php _e('favorites_index_no_recipes'); ?></p>
    <?php endif; ?>

    <h2><?php _e('favorites_index_articles'); ?></h2>
    
    <?php if (!empty($articles)): ?>
        <div class="articles-grid">
            <?php foreach ($articles as $article): ?>
                <article class="article-card">
                    <div class="article-image-container">
                        <a href="<?= base_href('/article/' . $article['article_id']) ?>" style="text-decoration: none;">
                            <img src="<?= htmlspecialchars($article['image']) ?>"
                                alt="<?= htmlspecialchars($article['title']) ?>"
                                class="article-image"
                                loading="lazy"
                                onerror="this.src='assets/img/no-image.png'"></a>
                        <div class="article-meta">
                            <span class="article-badge"><?php _e('favorites_index_badge_article'); ?></span>
                            <span class="article-locale"><?= htmlspecialchars($article['locale']) ?></span>
                        </div>
                    </div>
                    <div class="article-content">
                        <h2 class="article-title">
                            <a href="/article/<?= $article['article_id'] ?>" style="text-decoration: none;">
                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </h2>
                        <p class="article-excerpt">
                            <?= htmlspecialchars(mb_substr($article['content'], 0, 150)) ?>...
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p><?php _e('favorites_index_no_articles'); ?></p>
    <?php endif; ?>
</div>

<style>
    .create-actions {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        margin: 25px 0 35px;
    }
    
    .action-button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: linear-gradient(to bottom, #4CAF50, #388E3C);
        color: white;
        text-decoration: none; 
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-align: center;
    }
    
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        background: linear-gradient(to bottom, #43A047, #2E7D32);
        color: whitesmoke;
        text-decoration: none;
        transform: scale(1.05);
    }
    
    .action-button:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .action-button .icon {
        fill: white; 
        stroke: white;
    }
    
    a {
        text-decoration: none;
    }
    

</style>