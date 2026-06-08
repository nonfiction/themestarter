<?php

use Nonfiction\Theme\Assets;
use Nonfiction\Theme\Timber\Block;

Block::register_block_type(__DIR__ . '/block.json', [
  'render' => function (&$context) {
    $context['title'] ??= '';
    $context['content'] ??= '';
    $context['buttonText'] ??= 'Learn More';
    $context['buttonLink'] ??= '';
    $context['imageUrl'] ??= '';
    $context['imageUrl2x'] ??= '';

    $context['imageUrl'] = Assets::normalize_asset_url($context['imageUrl']);
    $context['imageUrl2x'] = Assets::normalize_asset_url($context['imageUrl2x']);

    return <<<'TWIG'
      <div class="grid-col {{ content ? 'has-content no-stretch' : '' }} {{ imageUrl ? '' : 'no-image' }}">
        {% if buttonLink %}<a href="{{ buttonLink }}" class="grid-item">{% else %}<div class="grid-item">{% endif %}
          {% if imageUrl %}
            <div class="bg-stretch">
              <span data-srcset="{{ imageUrl }}, {{ imageUrl2x ? imageUrl2x : imageUrl }} 2x"></span>
            </div>
          {% endif %}
          <div class="item-content">
            <h2 class="item-title">{{ title }}</h2>
          </div>
          {% if content %}<p class="item-text">{{ content }}</p>{% endif %}
          {% if buttonLink %}
            <div class="item-btn">
              <span class="btn btn-default">{{ buttonText }}</span>
            </div>
          {% endif %}
        {% if buttonLink %}</a>{% else %}</div>{% endif %}
      </div>
    TWIG;
  },
]);
