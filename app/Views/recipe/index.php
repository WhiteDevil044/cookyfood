<?php

use Framework\Auth;


if (!empty($recipe)) {
    $r = $recipe;
?>
    <div class="container-recipe-in-page">
        <main>
            <header class="recipe-header">
                <h1><?= htmlspecialchars($r['title']) ?></h1>

                <div class="recipe-meta">
                    <div class="meta-item">
                        <svg width="30px" height="30px" viewBox="0 0 49 44" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="22" cy="22" r="20" fill="black" />
                            <path d="M22 2 A20 20 0 0 1 42 22 L22 22 Z" fill="white" />
                        </svg>
                        <span><?= $r['time_cooking'] ?> мин</span>
                    </div>
                </div>

                <img src="<?= htmlspecialchars($recipe['image']) ?>"
                    alt="<?= htmlspecialchars($recipe['title']) ?>"
                    class="recipe-image"
                    loading="lazy"
                    onerror="this.src='assets/img/no-image.png'">
            </header>

            <div class="recipe-container">
                <section class="ingredients-card">
                    <h2 class="card-title">Ингредиенты</h2>
                    <ul class="ingredients-list">
                        <?php
                        $ingredients = json_decode($r['ingredients'], true);
                        if ($ingredients && is_array($ingredients)) {
                            foreach ($ingredients as $ingredient) {
                                echo '<li class="ingredient">' . htmlspecialchars($ingredient) . '</li>';
                            }
                        }
                        ?>
                    </ul>
                </section>

                <section class="instructions-card">
                    <h2 class="card-title">Приготовление</h2>
                    <ol class="instructions-list">
                        <?php
                        $instructions = json_decode($r['instructions'], true);
                        if ($instructions && is_array($instructions)) {
                            foreach ($instructions as $step) {
                                echo '<li class="instruction-step">' . htmlspecialchars($step) . '</li>';
                            }
                        }
                        ?>
                    </ol>
                </section>
            </div>
            <?php if (check_auth()): ?>
                <form method="POST" action="<?= $isFavorite ? '/favorite/remove' : '/favorite/add' ?>">
                    <?= get_csrf_field(); ?>
                    <input type="hidden" name="type" value="recipe">
                    <input type="hidden" name="id" value="<?= $r['recipe_id'] ?>">
                    <button type="submit" class="favorite-button">
                        <span class="star-icon"><?= $isFavorite ? '★' : '☆' ?></span>
                        <span class="button-text"><?= $isFavorite ? 'удалить из избранного' : 'Добавить в избранное' ?></span>
                    </button>
                </form>


                <?php if ($r['user_id'] = Auth::user()['id']): ?>
                    <a href="<?= base_href("/recipe/edit/" . $r['recipe_id']); ?>" class="category-link">редактировать</a>

                <?php endif; ?>
            <?php endif; ?>

        </main>
    </div>
<?php } else { ?>
    <p class="error-message">Рецепт не найден.</p>
<?php } ?>