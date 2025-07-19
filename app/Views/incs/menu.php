<nav class="navbar">
    <button class="menu-toggle" aria-label="<?php _e('menu_open'); ?>">☰</button>
    <ul class="menu-list">
        <li role="none"><a href="<?= base_href('/recipes'); ?>" class="menu-link" role="menuitem"><?php _e('menu_recipes'); ?></a></li>
        <li role="none"><a href="<?= base_href('/articles'); ?>" class="menu-link" role="menuitem"><?php _e('menu_articles'); ?></a></li>
        <li role="none"><a href="<?= base_href('/selections'); ?>" class="menu-link" role="menuitem"><?php _e('menu_selections'); ?></a></li>


        <?php if (check_auth()): ?>
            <li role="none"><a href="<?= base_href('/favorites'); ?>" class="menu-link" role="menuitem"><?php _e('menu_favorites'); ?></a></li>
            <li role="none"><a href="<?= base_href('/calculator'); ?>" class="menu-link" role="menuitem"><?php _e('menu_calculator'); ?></a></li>
            <li role="none"><a href="<?= base_href('/logout'); ?>" class="menu-link" role="menuitem"><?php _e('menu_logout'); ?></a></li>
            <?php else: ?>
                <li role="none"><a href="<?= base_href('/register'); ?>" class="menu-link" role="menuitem"><?php _e('menu_register'); ?></a></li>
                <li role="none"><a href="<?= base_href('/login'); ?>" class="menu-link" role="menuitem"><?php _e('menu_login'); ?></a></li>
        <?php endif; ?>
    </ul>

    <div class="block-log-and-lang">
        <img class="logo" src="<?= base_url('/assets/logo.svg'); ?>" alt="logo">
        <?php $request_uri = uri_without_lang(); ?>
        <ul>
            <?php foreach (LANGS as $k => $v): ?>
                <?php if (app()->get('lang')['code'] == $k) continue; ?>
                <?php if ($v['base'] == 1): ?>
                    <li><a class="dropdown-item" href="<?= base_url("{$request_uri}"); ?>"><?= $v['title']; ?></a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="<?= base_url("/{$k}{$request_uri}"); ?>"><?= $v['title']; ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
<script src="<?= base_url('/assets/js/script.js'); ?>"></script>
