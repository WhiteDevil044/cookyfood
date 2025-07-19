<?php

use Framework\Auth;

?>
<div class="article-hero">
    <h1 align="center"><?php _e('article_index_title'); ?></h1>
</div>
<article class="post">
    <div class="post__meta">
        <div class="post__meta-item">
            <i class="fas fa-calendar"></i> <?= $formattedDate ?>
        </div>
    </div>

    <h1 class="post__title"><?= htmlspecialchars($article['title']) ?></h1>

    <div class="post__content">
        <?php
        $paragraphs = $article['paragraphs'] ?? '[]';
        foreach ($paragraphs as $paragraph):
        ?>
            <p class="post__paragraph"><?= nl2br(htmlspecialchars($paragraph)) ?></p>
        <?php endforeach; ?>
    </div>

    <?php if (check_auth()): ?>
        <form class="article-form-favorite" method="POST" action="<?= $isFavorite ? '/favorite/remove' : '/favorite/add' ?>">
            <?= get_csrf_field(); ?>
            <input type="hidden" name="type" value="article">
            <input type="hidden" name="id" value="<?= $article['article_id'] ?>">
            <button type="submit" class="favorite-button">
                <span class="star-icon"><?= $isFavorite ? '★' : '☆' ?></span>
                <span class="button-text"><?= $isFavorite ? 'удалить из избранного' : 'Добавить в избранное' ?></span>
            </button>
        </form>
        <?php if ($article['user_id'] = Auth::user()['id']): ?>
            <a href="<?= base_href("/article/edit/" . $article['article_id']); ?>" class="category-link">редактировать</a>

        <?php endif; ?>
    <?php endif; ?>
</article>