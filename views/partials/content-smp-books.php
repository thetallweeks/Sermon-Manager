<?php

global $post;

if (!empty($GLOBALS['wpfc_partial_args'])) {
  extract($GLOBALS['wpfc_partial_args']); // phpcs:ignore
}

foreach (array(
  'old_testament',
  'new_testament',
  'terms'
) as $required_variable) {
  if (!isset($$required_variable)) {
    echo '<p><b>Sermon Manager</b>: Partial "<i>' . str_replace('.php', '', basename(__FILE__)) . '</i>" loaded incorrectly.</p>';
    return;
  }
}
?>

<div class="smp-books-list">
  <div class="row">
    <div class="smp-list col-sm-12 col-md-6">
      <h4 class="smp-list__title">Old Testament</h4>
      <div>
        <?php
        echo wpfc_get_partial('content-smp-book-list', array(
          'books'      => $old_testament,
          'terms'      => $terms,
        ));
        ?>
      </div>
    </div>
    <div class="smp-list col-sm-12 col-md-6">
      <h4 class="smp-list__title">New Testament</h4>
      <div>
        <?php
        echo wpfc_get_partial('content-smp-book-list', array(
          'books'      => $new_testament,
          'terms'      => $terms,
        ));
        ?>
      </div>
    </div>
  </div>
</div>
