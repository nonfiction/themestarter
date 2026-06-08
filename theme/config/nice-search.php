<?php

// Redirects search results from /?s=query to /search/query/, converts %20 to +
add_action('template_redirect', function () {

  global $wp_rewrite;
  if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->get_search_permastruct()) {
    return;
  }

  $search_base = $wp_rewrite->search_base;
  $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
  if (is_search() && !is_admin() && strpos($request_uri, "/{$search_base}/") === false && strpos($request_uri, '&') === false) {
    wp_safe_redirect(get_search_link());
    exit();
  }

});

add_filter('wpseo_json_ld_search_url', function ($url) {
  return str_replace('/?s=', '/search/', $url);
});
