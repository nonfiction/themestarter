<?php

add_filter('script_loader_tag', function ($tag, $handle) {
  $module_handles = [
    'nf-admin-js',
    'nf-blocks-js',
    'nf-body-js',
    'nf-editor-js',
    'nf-head-js',
  ];

  if (! in_array($handle, $module_handles, true)) {
    return $tag;
  }

  if (preg_match('/\stype=(["\']).*?\1/', $tag)) {
    return preg_replace('/\stype=(["\']).*?\1/', ' type="module"', $tag, 1);
  }

  return preg_replace('/<script\s/', '<script type="module" ', $tag, 1);
}, 30, 2);
