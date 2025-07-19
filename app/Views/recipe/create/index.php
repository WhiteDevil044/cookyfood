
<div class="container-create">
    <h1><?php _e('recipe_create_title'); ?></h1>

    <form id="create-recipe-form" method="POST" enctype="multipart/form-data" action="/recipe/create">
        <input type="hidden" name="csrf_token"   value="<?= session()->get('csrf_token') ?>">

        <div class="form-group">
            <label class="form-label"><?php _e('recipe_create_label_title'); ?></label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label"><?php _e('recipe_create_label_description'); ?></label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label class="form-label"><?php _e('recipe_create_label_ingredients'); ?></label>
            <button type="button" class="add-field-btn" id="add-ingredient">+ <?php _e('recipe_create_btn_add_ingredient'); ?></button>
            <div class="dynamic-fields" id="ingredients-fields">
                <div class="dynamic-field">
                    <input type="text" name="ingredients[]" class="form-control" required>
                    <button type="button" class="remove-field">×</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><?php _e('recipe_create_label_instructions'); ?></label>
            <button type="button" class="add-field-btn" id="add-instruction">+ <?php _e('recipe_create_btn_add_instruction'); ?></button>
            <div class="dynamic-fields" id="instructions-fields">
                <div class="dynamic-field">
                    <textarea name="instructions[]" class="form-control" required></textarea>
                    <button type="button" class="remove-field">×</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><?php _e('recipe_create_label_cooking_time'); ?></label>
            <input type="number" name="cooking_time" class="form-control" value="30" min="1" required>
        </div>
        <div class="form-group">
            <label class="form-label"><?php _e('recipe_create_label_image'); ?></label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label"><?php _e('recipe_create_label_category'); ?></label>
            <select name="category_id" class="form-control" required>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>">
                            <?= $cat['name'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value=""><?php _e('recipe_create_no_categories'); ?></option>
                <?php endif; ?>
            </select>
        </div>


        <div class="btn-group">
            <button type="submit" class="btn btn-primary"><?php _e('recipe_create_btn_submit'); ?></button>
            <a href="/recipes" class="btn btn-secondary"><?php _e('recipe_create_btn_cancel'); ?></a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('add-ingredient').addEventListener('click', function() {
            const container = document.getElementById('ingredients-fields');
            const field = document.createElement('div');
            field.className = 'dynamic-field';
            field.innerHTML = `
                <input type="text" name="ingredients[]" class="form-control" required>
                <button type="button" class="remove-field">×</button>
            `;
            container.appendChild(field);
            addRemoveListeners();
        });

        document.getElementById('add-instruction').addEventListener('click', function() {
            const container = document.getElementById('instructions-fields');
            const field = document.createElement('div');
            field.className = 'dynamic-field';
            field.innerHTML = `
                <textarea name="instructions[]" class="form-control" required></textarea>
                <button type="button" class="remove-field">×</button>
            `;
            container.appendChild(field);
            addRemoveListeners();
        });

        function addRemoveListeners() {
            document.querySelectorAll('.remove-field').forEach(button => {
                button.addEventListener('click', function() {
                    if (this.closest('.dynamic-fields').children.length > 1) {
                        this.parentElement.remove();
                    }
                });
            });
        }

        document.getElementById('create-recipe-form').addEventListener('submit', function(e) {
            let valid = true;

            const requiredFields = this.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Пожалуйста, заполните все обязательные поля');
            }
        });

        addRemoveListeners();
    });
</script>

