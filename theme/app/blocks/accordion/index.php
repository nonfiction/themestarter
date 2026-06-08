<?php
use Nonfiction\Theme\Timber\Block;

Block::register_block_type( __DIR__ . '/block.json', [
  'render' => function( &$context ) {
    return <<<'TWIG'
      <ul class="wp-block-nf-accordion content-accordion">
        {{ inner }}
      </ul>
    TWIG;
  },
] );
