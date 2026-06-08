<?php

class Article extends Nonfiction\Theme\Timber\Post {
  public function archive_summary() {
    if ($this->post_excerpt) {
      return (string) $this->post_excerpt;
    }

    return wp_trim_words(wp_strip_all_tags($this->post_content), 28);
  }

  public static function archive_posts() {
    return static::get_posts([
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
    ]);
  }
}

add_action('init', function () {
  Article::register_post_type([
    'names' => [
      'label_single' => 'Article',
      'label_plural' => 'Articles',
      'slug_single' => 'article',
      'slug_plural' => 'articles',
    ],
    'menu_icon' => 'dashicons-media-document',
    'supports' => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
    'rewrite' => [
      'slug' => 'articles',
      'with_front' => false,
    ],
    'has_archive' => true,
  ]);
});
