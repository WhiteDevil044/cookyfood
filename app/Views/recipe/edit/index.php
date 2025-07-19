<div class="container-create">
    <h1>Редактирование рецепта</h1>

    <form id="edit-recipe-form" method="POST" action="/recipe/edit/<?= $recipe['recipe_id'] ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= session()->get('csrf_token') ?>">
        <div class="form-group">
            <label class="form-label">Название рецепта*</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($recipe['title']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Описание*</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($recipe['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Ингредиенты*</label>
            <button type="button" class="add-field-btn" id="add-ingredient">+ Добавить ингредиент</button>
            <div class="dynamic-fields" id="ingredients-fields">
                <?php
                $ingredients = json_decode($recipe['ingredients'], true);
                foreach ($ingredients as $ingredient):
                ?>
                    <div class="dynamic-field">
                        <input type="text" name="ingredients[]" class="form-control" value="<?= htmlspecialchars($ingredient) ?>" required>
                        <button type="button" class="remove-field">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Шаги приготовления*</label>
            <button type="button" class="add-field-btn" id="add-instruction">+ Добавить шаг</button>
            <div class="dynamic-fields" id="instructions-fields">
                <?php
                $instructions = json_decode($recipe['instructions'], true);
                foreach ($instructions as $instruction):
                ?>
                    <div class="dynamic-field">
                        <textarea name="instructions[]" class="form-control" required><?= htmlspecialchars($instruction) ?></textarea>
                        <button type="button" class="remove-field">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Время приготовления (минуты)*</label>
            <input type="number" name="cooking_time" class="form-control" value="<?= $recipe['time_cooking'] ?>" min="1" required>
        </div>
        
        
        <div class="form-group">
            <label class="form-label">Добавьте фото</label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label class="shine-btn">
                    <input type="file" name="image" hidden required>
                    <span>Выберите файл</span>
                </label>
                <span id="file-name"><?= isset($recipe['image']) ? htmlspecialchars($recipe['image']) : 'Файл не выбран' ?></span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Категории</label>
            <select name="category_id" class="form-control" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_id'] ?>"
                        <?= $category['category_id'] == $recipe['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="/recipes" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
    <a href="<?= base_href("/recipe/delete/" . $recipe['recipe_id']); ?> " style="color:red;">удалить</a>

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

        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const fileName = e.target.files.length ? e.target.files[0].name : 'Файл не выбран';
            document.getElementById('file-name').textContent = fileName;
        });

        addRemoveListeners();
    });
</script>
