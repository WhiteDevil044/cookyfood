<div class="container-create">
    <h1><?php _e('article_create_title'); ?></h1>

    <form id="create-article-form" method="POST" enctype="multipart/form-data" action="/article/create">
        <input type="hidden" name="csrf_token" value="<?= session()->get('csrf_token') ?>">

        <div class="form-group">
            <label class="form-label"><?php _e('article_create_label_title'); ?></label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label"><?php _e('article_create_label_paragraphs'); ?></label>
            <button type="button" class="add-field-btn" id="add-paragraph">+ <?php _e('article_create_btn_add_paragraph'); ?></button>
            <div class="dynamic-fields" id="paragraphs-fields">
                <div class="dynamic-field">
                    <textarea name="paragraphs[]" class="form-control" required></textarea>
                    <button type="button" class="remove-field">×</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label"><?php _e('article_create_label_image'); ?></label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <label class="shine-btn">
                    <input type="file" name="image" hidden>
                    <span><?php _e('article_create_btn_choose_file'); ?></span>
                </label>
                <span id="file-name"><?= isset($article['image']) ? htmlspecialchars($article['image']) : _e('article_create_file_not_chosen') ?></span>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary"><?php _e('article_create_btn_submit'); ?></button>
            <a href="/articles" class="btn btn-secondary"><?php _e('article_create_btn_cancel'); ?></a>
        </div>
    </form>
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