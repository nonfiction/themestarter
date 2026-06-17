<?php
/**
 * Plugin Name: Agency Credit
 * Description: Adds an agency credit to the site footer.
 * Version: 0.1.0
 */

if (!defined('ABSPATH')) {
  exit;
}

add_action('wp_footer', function (): void {
  ?>
    <div class="agency-credit">
        handcrafted by
        <a href="https://www.nonfiction.ca/" target="_blank" rel="noopener">
            nonfiction studios
        </a>
    </div>
    <?php
}, 100);

add_action('wp_enqueue_scripts', function (): void {
  wp_register_style('agency-credit', false, [], '0.1.0');
  wp_enqueue_style('agency-credit');

  wp_add_inline_style('agency-credit', '
        .agency-credit {
            padding: 1rem;
            font-size: 0.875rem;
            text-align: center;
        }
    ');
});
