<?php

add_filter('block_editor_settings_all', function ($settings) {
  $editor_css_path = get_template_directory() . '/app/views/css/editor-content.css';

  if (! file_exists($editor_css_path)) {
    return $settings;
  }

  $css = file_get_contents($editor_css_path);

  if (! is_string($css) || $css === '') {
    return $settings;
  }

  $settings['styles'] ??= [];
  $settings['styles'][] = [
    'css' => $css,
  ];

  return $settings;
});
