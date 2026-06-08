<?php

use Nonfiction\Theme\App;

// Maybe flush?
add_action('init', function () {
  if (! is_blog_installed()) {
    return;
  }
  if ('1' !== get_option('nf_flushed')) {
    App::flush();
    update_option('nf_flushed', '1');
  }
});

// Add flush page to general options
add_action('admin_menu', function () {
  add_submenu_page(
    'options-general.php',
    'Flush',
    'Flush',
    'manage_options',
    'flush',
    function () {
      App::flush();
      update_option('nf_flushed', '1');
      echo '<div class="wrap"><h1>Flush</h1><p>Done.</p></div>';
    },
    100,
  );
}, 100);

// Add flush link to admin bar
add_action('admin_bar_menu', function ($admin_bar) {

  $admin_bar->add_menu([
    'id' => 'flush',
    'href' => admin_url('options-general.php?page=flush'),
    'parent' => 'top-secondary',
    'title' => 'Flush',
  ]);

}, 100);
