<?php

global $post;

if (!empty($GLOBALS['wpfc_partial_args'])) {
  extract($GLOBALS['wpfc_partial_args']); // phpcs:ignore
}

foreach (array(
  'hide_series',
  'hide_books',
  'hide_topics',
) as $required_variable) {
  if (!isset($$required_variable)) {
    echo '<p><b>Sermon Manager</b>: Partial "<i>' . str_replace('.php', '', basename(__FILE__)) . '</i>" loaded incorrectly.</p>';
    return;
  }
}
?>

<div class="smp-filters">
  <div>
    <ul class="smp-filters-tabs smp-filters-tabs--primary">
      <li class="smp-filters-tabs__tab">
        <a data-section="current-series">View Current Series</a>
      </li>
      <li class="smp-filters-tabs__tab">
        <a data-section="all-sermons">View All Sermons</a>
      </li>
    </ul>
  </div>
  <div>
    <ul class="smp-filters-tabs smp-filters-tabs--secondary">
      <li class="smp-filters-tabs__tab">
        <span>Filter By</span>
      </li>
      <?php if (SM_Shortcodes::should_show($hide_series)) : ?>
        <li class="smp-filters-tabs__tab">
          <a data-section="series-list">Series</a>
        </li>
      <?php endif ?>
      <?php if (SM_Shortcodes::should_show($hide_books)) : ?>
        <li class="smp-filters-tabs__tab">
          <a data-section="books-list">Scripture</a>
        </li>
      <?php endif ?>
      <?php if (SM_Shortcodes::should_show($hide_topics)) : ?>
        <li class="smp-filters-tabs__tab">
          <a data-section="topics-list">Topics</a>
        </li>
      <?php endif ?>
    </ul>
  </div>
</div>
