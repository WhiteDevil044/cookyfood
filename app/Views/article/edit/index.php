<div class="container-create">
    <h1>Редактирование статьи</h1>

    <form id="edit-recipe-form" method="POST"  enctype="multipart/form-data" action="/article/edit/<?= $article['article_id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= session()->get('csrf_token') ?>">
        <div class="form-group">
            <label class="form-label">Название статьи*</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($article['title']) ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Параграфы*</label>
            <button type="button" class="add-field-btn" id="add-paragraph">+ Добавить параграф</button>
            <div class="dynamic-fields" id="paragraphs-fields">
                <?php
                $paragraphs = $article['paragraphs'] ?? [];
                foreach ($paragraphs as $paragraph):
                ?>
                    <div class="dynamic-field">
                        <textarea name="paragraphs[]" class="form-control" required><?= htmlspecialchars($paragraph) ?></textarea>
                        <button type="button" class="remove-field">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

       <div class="form-group">
            <label class="form-label">Добавьте фото</label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label class="shine-btn">
                    <input type="file" name="image" hidden required>
                    <span>Выберите файл</span>
                </label>
                <span id="file-name"><?= isset($article['image']) ? htmlspecialchars($article['image']) : 'Файл не выбран' ?></span>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            <a href="/articles" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
    <a href="<?= base_href("/article/delete/" . $article['article_id']); ?> "style="color:red;" >удалить</a>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
  
        document.getElementById('add-paragraph').addEventListener('click', function() {
            const container = document.getElementById('paragraphs-fields');
            const field = document.createElement('div');
            field.className = 'dynamic-field';
            field.innerHTML = `
                <textarea name="paragraphs[]" class="form-control" required></textarea>
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

        document.getElementById('edit-recipe-form').addEventListener('submit', function(e) {
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