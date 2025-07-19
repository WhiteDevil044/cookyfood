<section class="search-hero">
    <div class="search-container">
        <h1 class="main-heading"><?php _e('recipes_index_title'); ?></h1>
        <p class="action-question"><?php _e('recipes_index_action'); ?></p>
        <form class="search-form" method="GET" action="">
            <div class="search-input-wrapper">
                <input type="text" name="search"
                    class="search-input" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit" class="search-button"><?php _e('recipes_index_btn_search'); ?></button>
            </div>
        </form>
    </div>
</section>
<div class="container">
    <main class="recipe-grid">
        <? if (!isset($recipes)): ?>
            <?php _e('recipes_index_not_found'); ?>
        <? endif ?>
        <?php foreach ($recipes as $recipe): ?>
            <article class="recipe-card">
                <div class="recipe-image-container">
                    <a href="<?= base_href('/recipe/' . $recipe['recipe_id']) ?>">
                        <img src="<?= htmlspecialchars($recipe['image']) ?>"
                            alt="<?= htmlspecialchars($recipe['title']) ?>"
                            class="recipe-image"
                            loading="lazy"
                            onerror="this.src='assets/img/no-image.png'"></a>
                    <div class="recipe-tags">
                        <span class="recipe-badge"><?php _e('recipes_index_badge'); ?></span>
                        <span class="cooking-time">⏱ <?= $recipe['time_cooking'] ?> <?php _e('recipes_index_min'); ?></span>
                    </div>
                </div>
                <div class="recipe-content">
                    <h2 class="recipe-title">
                        <a href="<?= base_href('/recipe/' . $recipe['recipe_id']) ?>">
                            <?= htmlspecialchars($recipe['title']) ?>
                        </a>
                    </h2>
                    <p class="recipe-description">
                        <?= htmlspecialchars($recipe['description']) ?>
                    </p>
                </div>
            </article>
        <?php endforeach; ?>

    </main> <a href="<?= base_href('/selections') ?>" class="category-link"><?php _e('recipes_index_to_categories'); ?></a>

</div>



<?php echo $pagination  ?>