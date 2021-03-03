<?php

global $post;

if (!empty($GLOBALS['wpfc_partial_args'])) {
  extract($GLOBALS['wpfc_partial_args']); // phpcs:ignore
}

if (!function_exists('get_item_in_array')) {
  function get_item_in_array($array, $key, $value) {
    $item = null;
    foreach ($array as $struct) {
      if ($value == $struct->$key) {
        $item = $struct;
        break;
      }
    }
    return $item;
  }
}

foreach (array(
  'books',
  'terms'
) as $required_variable) {
  if (!isset($$required_variable)) {
    echo '<p><b>Sermon Manager Plus</b>: Partial "<i>' . str_replace('.php', '', basename(__FILE__)) . '</i>" loaded incorrectly.</p>';
    return;
  }
}
?>

<ul>
  <?php foreach ($books as $key => $value) : ?>
    <li>
      <?php
        $term = get_item_in_array($terms, 'name', $value);
      ?>
      <?php if ($term->count) : ?>
        <a href="<?php echo esc_url(get_term_link($term, $term->taxonomy)) ?>">
          <?php echo $value ?><span class="smp-list__count"><?php echo $term->count ?></span>
        </a>
      <?php else : ?>
        <?php echo $value ?>
      <?php endif ?>
    </li>
  <?php endforeach; ?>
</ul>
