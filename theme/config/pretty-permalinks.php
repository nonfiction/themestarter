<?php

$pretty_permalinks_enabled = false;

add_action('admin_init', function () use (&$pretty_permalinks_enabled) {
  if (! current_user_can('manage_options')) {
    return;
  }

  if (! empty(get_option('permalink_structure'))) {
    return;
  }

  update_option('permalink_structure', '/%postname%/');
  flush_rewrite_rules();

  $pretty_permalinks_enabled = true;
});

add_action('admin_notices', function () use (&$pretty_permalinks_enabled) {
  if (! $pretty_permalinks_enabled) {
    return;
  }

  $url = admin_url('options-permalink.php');

  printf(
    '<div class="notice notice-success"><p>%s <a href="%s">%s</a>.</p></div>',
    esc_html__('Post name permalinks have been enabled for this site.', 'nf'),
    esc_url($url),
    esc_html__('Review Settings > Permalinks', 'nf'),
  );
});
