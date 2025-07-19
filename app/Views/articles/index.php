<section class="search-hero">
    <div class="search-container">
        <h1 class="main-heading"><?php _e('articles_index_title'); ?></h1>
        <p class="action-question"><?php _e('articles_index_action'); ?></p>
        <form class="search-form" method="GET" action="">
            <div class="search-input-wrapper">
                <input type="text" name="search"
                    class="search-input" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="search-button"><?php _e('articles_index_btn_search'); ?></button>
            </div>
        </form>
    </div>
</section>
<style>

</style>
<div class="container">
    <main class="articles-grid">
        <? if (!isset($articles)): ?>
            <?php _e('articles_index_not_found'); ?>
        <? endif ?>
        <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <div class="article-image-container">
                    <a href="<?= base_href('/article/' . $article['article_id']) ?>">
                        <img src="<?= htmlspecialchars($article['image']) ?>"
                            alt="<?= htmlspecialchars($article['title']) ?>"
                            class="article-image"
                            loading="lazy"
                            onerror="this.src='assets/img/no-image.png'"></a>
                    <div class="article-meta">
                        <span class="article-badge"><?php _e('articles_index_badge'); ?></span>
                        <span class="article-locale"><?= htmlspecialchars($article['locale']) ?></span>
                    </div>
                </div>
                <div class="article-content">
                    <h2 class="article-title">
                        <a href="/article/<?= $article['article_id'] ?>">
                            <?= htmlspecialchars($article['title']) ?>
                        </a>
                    </h2>
                    <p class="article-excerpt">
                        <?= htmlspecialchars(mb_substr($article['content'], 0, 150)) ?>...
                    </p>
                </div>
            </article>
        <?php endforeach; ?>
    </main>
</div>

<?php echo $pagination   ?>