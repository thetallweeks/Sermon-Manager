<?php

global $post;

if (!empty($GLOBALS['wpfc_partial_args'])) {
  extract($GLOBALS['wpfc_partial_args']); // phpcs:ignore
}

foreach (array(
  'terms'
) as $required_variable) {
  if (!isset($$required_variable)) {
    echo '<p><b>Sermon Manager</b>: Partial "<i>' . str_replace('.php', '', basename(__FILE__)) . '</i>" loaded incorrectly.</p>';
    return;
  }
}
?>

<div class="smp-list smp-topics-list">
  <div>
    <h4 class="smp-list__title">Topics</h4>
    <div>
      <ul>
        <?php foreach ($terms as $term) : ?>
          <li>
            <?php if ($term->count) : ?>
              <a href="<?php echo esc_url(get_term_link($term, $term->taxonomy)) ?>">
                <?php echo $term->name ?><span class="smp-list__count"><?php echo $term->count ?></span>
              </a>
            <?php else : ?>
              <?php echo $term->name ?>
            <?php endif ?>
          <?php endforeach; ?>
        </li>
      </ul>
    </div>
  </div>
</div>
