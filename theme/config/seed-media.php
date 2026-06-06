<?php
use Nonfiction\Theme\Assets;

add_filter( 'the_content', [ Assets::class, 'normalize_seed_media_html' ], 20 );
add_action( 'template_redirect', [ Assets::class, 'maybe_serve_seed_asset' ], 0 );
