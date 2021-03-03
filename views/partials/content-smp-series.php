<?php

global $post;

if (!empty($GLOBALS['wpfc_partial_args'])) {
  extract($GLOBALS['wpfc_partial_args']); // phpcs:ignore
}

foreach (array(
  'terms',
  'size',
  'hide_title'
) as $required_variable) {
  if (!isset($$required_variable)) {
    echo '<p><b>Sermon Manager</b>: Partial "<i>' . str_replace('.php', '', basename(__FILE__)) . '</i>" loaded incorrectly.</p>';
    return;
  }
}

// NOTE: This is copy-pasted from Sermon Manager
function get_series_image_id($series = 0)
{
  if (0 !== $series && is_numeric($series)) {
    $series = intval($series);
  } elseif ($series instanceof WP_Term) {
    $series = $series->term_id;
  } else {
    return null;
  }

  $associations = sermon_image_plugin_get_associations();
  $tt_id        = absint($series);

  if (array_key_exists($tt_id, $associations)) {
    $id = absint($associations[$tt_id]);

    return $id;
  }

  return null;
}

// TODO: If no image is found, it would be good to use the image from a sermon in the series
function get_series_image($term, $size)
{
  $image_id = get_series_image_id($term);
  return wp_get_attachment_image($image_id, $size);
}
?>

<div class="smp-series-grid<?php if ($false != $hide_title && 'no' != $hide_title) : ?> smp-series-grid--no-title<?php endif ?>">
  <?php foreach ($terms as $term) : ?>
    <?php $img = get_series_image($term, $size) ?>
    <?php if ($img) : ?>
      <div class="smp-series-grid__item">
        <a href="<?php echo esc_url(get_term_link($term, $term->taxonomy)) ?>">
          <?php echo $img ?>
          <?php if (false == $hide_title || 'no' == $hide_title) : ?>
            <h5 class="smp-series-grid__name"><?php echo $term->name ?></h5>
          <?php endif ?>
        </a>
      </div>
    <?php endif ?>
  <?php endforeach ?>
</div>
