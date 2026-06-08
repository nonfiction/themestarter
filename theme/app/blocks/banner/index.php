<?php

use Nonfiction\Theme\Assets;
use Nonfiction\Theme\Timber\Block;

use function Nonfiction\Theme\css;

Block::register_block_type(__DIR__ . '/block.json', [
  'render' => function (&$context) {
    $context['heading'] ??= '';
    $context['content'] ??= '';
    $context['background_url'] ??= Assets::asset_uri('app/views/img/default.jpg');
    $context['background_url'] = Assets::normalize_asset_url($context['background_url']);
    $context['style'] = css([
      'background-image' => "url({$context['background_url']})",
    ]);

    return <<<'TWIG'
      <div class="banner-block">
        <div class="intro-section">
          <div class="bg-stretch" style="{{ style }}">
            <span data-srcset="{{ background_url }}, {{ background_url }} 2x"></span>
          </div>
          <div class="container">
            <div class="intro-text">
              {% if heading %}<strong class="intro-title">{{ heading }}</strong>{% endif %}
              {% if content %}<div class="intro-copy">{{ content }}</div>{% endif %}
            </div>
          </div>
        </div>
      </div>
    TWIG;
  },
]);
