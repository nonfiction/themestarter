<?php

// Skip in admin but allow in ajax
if ((is_admin() && !wp_doing_ajax())) {
  return;
}

// Skip in sitemap.
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only request check.
if (isset($_GET['sitemap'])) {
  return;
}

// Skip login/register screens
if (in_array(($GLOBALS['pagenow'] ?? ''), ['wp-login.php', 'wp-register.php'])) {
  return;
}

// Relative URLs
foreach ([
  'bloginfo_url',
  'the_permalink',
  'wp_list_pages',
  'wp_list_categories',
  'wp_get_attachment_url',
  'the_content_more_link',
  'the_tags',
  'get_pagenum_link',
  'get_comment_link',
  'month_link',
  'day_link',
  'year_link',
  'term_link',
  'the_author_posts_link',
  'script_loader_src',
  'style_loader_src',
  'theme_file_uri',
  'parent_theme_file_uri',
] as $tag) {
  add_filter($tag, '\\Nonfiction\\Theme\\make_link_relative', 10, 1);
}

add_filter('wp_calculate_image_srcset', function ($sources) {
  foreach ((array) $sources as $source => $src) {
    $sources[$source]['url'] = \Nonfiction\Theme\make_link_relative($src['url']);
  }

  return $sources;
});

// Compatibility with The SEO Framework
add_action('the_seo_framework_do_before_output', function () {
  remove_filter('wp_get_attachment_url', '\\Nonfiction\\Theme\\make_link_relative');
});

add_action('the_seo_framework_do_after_output', function () {
  add_filter('wp_get_attachment_url', '\\Nonfiction\\Theme\\make_link_relative');
});
