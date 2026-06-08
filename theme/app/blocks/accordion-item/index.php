<?php
use Nonfiction\Theme\Timber\Block;

Block::register_block_type( __DIR__ . '/block.json', [
  'render' => function( &$context ) {
    $context['title'] = wp_strip_all_tags( $context['title'] ?? '' );
    $context['itemId'] = sanitize_title( $context['title'] );
    $context['open'] = ! empty( $context['open'] );

    return <<<'TWIG'
      <li{% if itemId %} id="{{ itemId }}"{% endif %} class="wp-block-nf-accordion-item{{ open ? ' slide-open' : '' }}">
        <h3 class="accordion-opener">{{ title }}</h3>
        <div class="accordion-slide js-acc-hidden">
          <div class="slide-inner">{{ inner|raw }}</div>
        </div>
      </li>
    TWIG;
  },
] );
