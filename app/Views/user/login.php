<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title"><?php _e('login_title'); ?></h2>
        </div>

        <form class="auth-form ajax-form" action="<?= base_href('/login'); ?>" method="post">
            <?= get_csrf_field(); ?>

            <div class="input-group">
                <label for="email" class="input-label"><?php _e('login_email'); ?></label>
                <input name="email" type="email"
                    class="text-input <?= get_validation_class('email'); ?>"
                    id="email"

                    required
                    value="<?= htmlspecialchars(old('email')); ?>">
                <div data-error-field="email">
                    <?= get_errors('email'); ?>
                </div>
            </div>

            <div class="input-group">
                <label for="password" class="input-label"><?php _e('login_password'); ?></label>
                <input name="password" type="password"
                    class="text-input <?= get_validation_class('password'); ?>"
                    id="password"

                    required>
                <div data-error-field="password">
                    <?= get_errors('password'); ?>
                </div>
            </div>
            <button class="primary-btn" type="submit"><?php _e('login_btn'); ?></button>
        </form>
    </div>
</div>

<?php
session()->remove('form_data');
session()->remove('form_errors');
?>