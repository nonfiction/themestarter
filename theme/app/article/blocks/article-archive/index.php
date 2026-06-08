<?php
use Nonfiction\Theme\Timber\Block;

Block::register_block_type( __DIR__ . '/block.json', [
  'render' => function( &$context ) {
    $context['items'] = Article::archive_posts();

    if ( empty( $context['items'] ) ) {
      return '<p>No articles found.</p>';
    }

    return <<<'TWIG'
      <div class="article-list">
        {% for item in items %}
          <article class="article-list__item" id="article-{{ item.ID }}">
            <p class="eyebrow">Article</p>
            <h2 class="article-list__title"><a href="{{ item.link }}">{{ item.title }}</a></h2>
            {% if item.archive_summary %}
              <p>{{ item.archive_summary }}</p>
            {% endif %}
          </article>
        {% endfor %}
      </div>
    TWIG;
  },
] );
