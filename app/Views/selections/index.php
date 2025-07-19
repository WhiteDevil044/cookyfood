<section class="categories">
  <h2 class="categories__title"><?php _e('selections_index_title'); ?></h2>
  <ul class="categories__list">
    <?php foreach ($categories as $category): ?>
      <li class="categories__item">
        <a href="<?=base_href('/selection/')?><?= translite($category['name']); ?>"
          class="categories__link">
          <?= $category['name'] ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
  <br>
  <br>
  <br>
  <br>
  <br>
</section>

