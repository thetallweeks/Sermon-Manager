<?php

global $post;

if (!empty($GLOBALS['wpfc_partial_args'])) {
  extract($GLOBALS['wpfc_partial_args']); // phpcs:ignore
}

$args = $GLOBALS['wpfc_partial_args'];

$sm_shortcodes = SM_Shortcodes::get_instance();

foreach (array(
  'hide_series',
  'hide_books',
  'hide_topics',
  'current_series'
) as $required_variable) {
  if (!isset($$required_variable)) {
    echo '<p><b>Sermon Manager</b>: Partial "<i>' . str_replace('.php', '', basename(__FILE__)) . '</i>" loaded incorrectly.</p>';
    return;
  }
}

$current_series_args = array(
  'series_name'    => $args['current_series']
);

$all_sermon_args = array(
  'orderby'    => $args['order_by'] ?: 'date',
  'per_page'   => $args['per_page'] ?: '20'
);

$series_list_args = array();

$book_list_args = array();

$topic_list_args = array();

?>

<?php echo $sm_shortcodes->display_sermon_filters($args); ?>

<div class="smp-content-section hidden" id="current-series">
<?php echo $sm_shortcodes->display_sermons_for_series($current_series_args); ?>
</div>

<div class="smp-content-section" id="all-sermons">
  <?php echo $sm_shortcodes->display_sermons($all_sermon_args); ?>
</div>

<?php if (SM_Shortcodes::should_show($hide_series)) : ?>
  <div class="smp-content-section hidden" id="series-list">
    <?php echo $sm_shortcodes->display_sermon_series_list($series_list_args); ?>
  </div>
<?php endif ?>

<?php if (SM_Shortcodes::should_show($hide_books)) : ?>
  <div class="smp-content-section hidden" id="books-list">
    <?php echo $sm_shortcodes->display_sermon_books_list($books_list_args); ?>
  </div>
<?php endif ?>

<?php if (SM_Shortcodes::should_show($hide_topics)) : ?>
  <div class="smp-content-section hidden" id="topics-list">
    <?php echo $sm_shortcodes->display_sermon_topics_list($topics_list_args); ?>
  </div>
<?php endif ?>

