<?php
use Nonfiction\Theme\App;
use Nonfiction\Theme\Assets;
use Nonfiction\Theme\Timber\Menu;
use Nonfiction\Theme\Timber\Post;
use function Nonfiction\Theme\humanize;
use function Nonfiction\Theme\is_empty;
use function Nonfiction\Theme\titleize;

App::init( get_template_directory() );

// Import all post/block types
App::import([ 
  'app/*/index.php',           // post types
  'app/blocks/*/index.php',    // block types
  'app/*/blocks/*/index.php',  // block types nested under post types
  'config/*.php',              // config tweaks
]);

// Set directories where twig views are found
// "front.twig"
// "page/page-about.twig"
// "story/tease.twig"
// "story/blocks/story-heading/single.twig"
App::views([ 
  'app/views',  
  'app' 
]);

// Enqueue assets from build manifest
App::enqueue( 'dist/manifest.json' );

//
// Custom code below
//
add_filter( 'block_categories_all', function( $categories ) {
  $slug = 'custom';

  foreach ( $categories as $category ) {
    if ( ( $category['slug'] ?? '' ) === $slug ) {
      return $categories;
    }
  }

  array_unshift( $categories, [
    'slug' => $slug,
    'title' => 'Custom',
    'icon' => null,
  ] );

  return $categories;
} );

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'align-wide' );
remove_theme_support( 'core-block-patterns' );

add_action( 'after_setup_theme', function() {
  register_nav_menus([
    'primary' => 'Primary Navigation',
    'utility' => 'Utility Navigation',
    'footer' => 'Footer Navigation',
    'social' => 'Social Navigation',
  ]);
});

add_action( 'wp_enqueue_scripts', function() {
  wp_deregister_style('classic-theme-styles');
  wp_dequeue_style('classic-theme-styles');
}, 100);


// Set values used in most templates
add_filter( 'timber/context', function($context) {

  $context['site'] = new Timber\Site();
  $context['menu_primary'] = Menu::get_menu( 'primary' );
  $context['menu_utility'] = Menu::get_menu( 'utility' );
  $context['menu_footer'] = Menu::get_menu( 'footer' );
  $context['menu_social'] = Menu::get_menu( 'social' );

  $context['img'] = Assets::asset_uri( 'app/views/img' );
  $context['s'] = get_search_query();
  $context['site_year'] = date( 'Y' );
  $context['side_nav'] = Page::side_nav_context();

  $context['post'] = Timber::get_post();
  $context['posts'] = Timber::get_posts();

  return $context;

});


// Add some additional twig functions
add_filter( 'timber/twig', function( $twig ) {

  // Adding a function.
  $twig->addFunction( new Twig\TwigFunction( 'edit_post_link', 'edit_post_link' ) );

  // Query posts from twig
  $twig->addFunction( new Twig\TwigFunction( 'PostQuery', function( $args ) {
     return Post::get_posts( $args );
  }));

  // Adding functions as filters.
  $twig->addFunction( new Twig\TwigFunction( 'debug', function( $args ) {
    return '<pre>' . print_r($args, true) . '</pre>';
  }));

  $twig->addFilter( new Twig\TwigFilter( 'titleize', function( $input ) {
    return (is_empty($input)) ? '' : titleize($input);
  }));

  $twig->addFilter( new Twig\TwigFilter( 'humanize', function( $input ) {
    return (is_empty($input)) ? '' : humanize($input);
  }));

  $twig->addFilter( new Twig\TwigFilter( 'decode', function( $input ) {
    return (is_empty($input)) ? '' : base64_decode($input);
  }));

  $twig->addFilter( new Twig\TwigFilter( 'padded', function( $input ) {
    return (is_empty($input)) ? '' : str_pad($input, 2, '0', STR_PAD_LEFT);
  }));

  $twig->addFilter( new Twig\TwigFilter( 'currency', function( $input ) {
    return (is_empty($input)) ? '' : number_format((float)$input, 2, '.', '');
  }));

  $twig->addFilter( new Twig\TwigFilter( 'strtotime', function( $input ) {
    return (is_empty($input)) ? '' : strtotime($input);
  }));

  $twig->addFilter( new Twig\TwigFilter( 'extract_image', function( $input ) {
    if ( preg_match("%(?<=src=\")([^\"])+(png|jpg|jpeg|gif|svg)%i",$input,$result)) {
      return $result[0];
    }
  }));

  return $twig;

});
