<br>
<br>
<h1 align="center"><? echo $categoryName ?></h1>
<?php if (empty($recipes)): ?>
    <div class="alert alert-info text-center">
        <p><?= _('No recipes found in this category') ?></p>
    </div>
<?php else: ?>
    <div class="container">
        <main class="recipe-grid">

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
                            <span class="recipe-badge">Рецепт</span>
                            <span class="cooking-time">⏱ <?= $recipe['time_cooking'] ?> мин</span>
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
        </main>
    </div>
<?php endif; ?>
<br>
<br><br>
<br><br>
<br><br>
<br><br>
<br>