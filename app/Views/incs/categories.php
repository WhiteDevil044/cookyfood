<div class="form-group">
    <label class="form-label">Категории</label>
    <select name="category_id" class="form-control" required>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>">
                    <?= $cat['name'] ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">Нет доступных категорий</option>
        <?php endif; ?>
    </select>
</div>