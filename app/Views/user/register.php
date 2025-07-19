<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="auth-title"><?php _e('register_title'); ?></h2>
        </div>

        <form class="auth-form ajax-form" action="<?= base_href('/register'); ?>" method="post" id="registrationForm">
            <?= get_csrf_field(); ?>

            <div class="input-group">
                <label for="name" class="input-label"><?php _e('register_name'); ?></label>
                <input name="name" type="text"
                    class="text-input <?= get_validation_class('name'); ?>"
                    id="name"
                    value="<?= htmlspecialchars(old('name')); ?>" required>
                <?= get_errors('name'); ?>
            </div>

            <div class="input-group">
                <label for="email" class="input-label"><?php _e('register_email'); ?></label>
                <input name="email" type="email"
                    class="text-input <?= get_validation_class('email'); ?>"
                    id="email"
                    value="<?= htmlspecialchars(old('email')); ?>" required>
                <?= get_errors('email'); ?>
            </div>

            <div class="input-group">
                <label for="password" class="input-label"><?php _e('register_password'); ?></label>
                <input name="password" type="password"
                    class="text-input <?= get_validation_class('password'); ?>"
                    id="password" required>
                <?= get_errors('password'); ?>
            </div>

            <div class="input-group">
                <label for="confirmPassword" class="input-label"><?php _e('register_confirm_password'); ?></label>
                <input name="confirmPassword" type="password"
                    class="text-input <?= get_validation_class('confirmPassword'); ?>"
                    id="confirmPassword" required>
                <?= get_errors('confirmPassword'); ?>
            </div>

            <div class="agreement-group">
                <label class="checkbox-container">
                    <input type="checkbox" id="terms" name="confirmTerms">
                    <span class="checkmark"></span>
                    <span class="agreement-text">
                        I accept all <a href="/terms" class="text-link">terms & conditions</a>
                    </span>
                </label>
                <div id="termsError" class="error-message">

                </div>
            </div>

            <button class="primary-btn" type="submit"><?php _e('register_btn'); ?></button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registrationForm');
        const termsCheckbox = document.getElementById('terms');
        const termsError = document.getElementById('termsError');

        if (termsError.innerHTML.trim() === '') {
            termsError.style.display = 'none';
        }

        form.addEventListener('submit', function(e) {
            let isValid = true;

            if (!termsCheckbox.checked) {
                termsError.innerHTML = "accept the terms and conditions";
                termsError.style.display = 'block';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();

                termsError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        termsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                termsError.style.display = 'none';
            }
        });
    });
</script>

<style>
    .error-message {
        color: #e74c3c;
        margin-top: 8px;
        font-size: 14px;
        display: block;
    }

    .checkbox-container.error .checkmark {
        border: 1px solid #e74c3c;
    }
</style>

<?php
session()->remove('form_data');
session()->remove('form_errors');
?>