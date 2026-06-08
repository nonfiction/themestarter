<?php

// Native post type view helpers can live here when a starter project needs them.
//
class Post extends Nonfiction\Theme\Timber\Post
{
    // Hide builtin post type
    public static function hide()
    {

        // Hide on the side
        add_action('admin_menu', function () {
            remove_menu_page('edit.php');
        });

        // Hide on the top
        add_action('admin_bar_menu', function () {
            global $wp_admin_bar;
            $wp_admin_bar->remove_node('new-post');
        }, 80);

    }
}


add_action('init', function () {
    // Post::hide();
});
