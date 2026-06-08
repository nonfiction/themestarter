<?php

/**
 * Admin UI cleanup.
 *
 * This file replaces the old Intervention-based config with direct WordPress
 * hooks so the behavior stays local, explicit, and easy to maintain.
 *
 * Scope of tweaks in this file:
 * - simplify the admin bar
 * - hide help and update noise
 * - reduce profile fields for selected roles
 * - remove unused page/post editor panels
 * - trim dashboard widgets
 * - increase list table pagination
 */

// Remove the "Howdy," prefix from the account item in the admin bar.
// Use a very late priority because core rewrites the same node again at 9991
// in `wp_admin_bar_my_account_item()`.
add_action('admin_bar_menu', function ($admin_bar) {
  $node = $admin_bar->get_node('my-account');

  if (! $node || empty($node->title)) {
    return;
  }

  $node->title = preg_replace('/\s*Howdy,\s*/i', '', $node->title);
  $admin_bar->add_node($node);
}, 10000);

// Remove contextual help tabs from admin screens.
// We do this late enough that the current screen object is available, but the
// change is still per-screen and uses the normal core API.
add_action('admin_head', function () {
  $screen = get_current_screen();

  if ($screen) {
    $screen->remove_help_tabs();
  }
});

// Hide core update nags in the admin UI.
// This affects the visible notice area only. It does not disable updates,
// update checks, or any maintenance workflows.
add_action('admin_head', function () {
  remove_action('admin_notices', 'update_nag', 3);
  remove_action('network_admin_notices', 'update_nag', 3);
});

// Remove selected shortcut items from the admin bar.
add_action('admin_bar_menu', function ($admin_bar) {
  foreach ([ 'wp-logo', 'updates', 'comments', 'new-user', 'themes', 'customize' ] as $item) {
    $admin_bar->remove_node($item);
  }
}, 999);

// Hide profile sections for editor and author accounts on their own profile
// screen. This is visual cleanup for fields we do not want these roles to edit.
add_action('admin_head-profile.php', function () {
  hide_user_profile_sections([ 'options', 'names', 'contact' ], [ 'administrator', 'editor', 'author' ]);
});

// Apply the same profile cleanup when an admin edits an editor or author.
add_action('admin_head-user-edit.php', function () {
  hide_user_profile_sections([ 'options', 'names', 'contact' ], [ 'administrator', 'editor', 'author' ]);
});

// Prevent lower-value roles from being assignable in the user editor.
// This narrows the role list without changing role registration itself.
add_filter('editable_roles', function ($editable_roles) {
  foreach ([ 'subscriber', 'contributor' ] as $role) {
    unset($editable_roles[ $role ]);
  }

  return $editable_roles;
});

// Remove editor panels that are not used in this project.
// Page and post support flags stay registered globally; this only hides the
// corresponding UI in the edit screens.
add_action('admin_menu', function () {
  foreach ([ 'author', 'custom-fields', 'comments', 'trackbacks' ] as $feature) {
    remove_post_type_support('page', $feature);
  }

  foreach ([ 'custom-fields', 'comments', 'trackbacks' ] as $feature) {
    remove_post_type_support('post', $feature);
  }
}, 999);

// Remove default dashboard widgets so the landing screen stays minimal.
// Widgets are removed from both normal and side contexts because core and
// plugins may register them in either area.
add_action('wp_dashboard_setup', function () {
  foreach ([
    'dashboard_primary',
    'dashboard_quick_press',
    'dashboard_right_now',
    'dashboard_activity',
    'dashboard_recent_drafts',
    'dashboard_recent_comments',
    'dashboard_site_health',
    'dashboard_incoming_links',
    'dashboard_plugins',
  ] as $widget_id) {
    remove_meta_box($widget_id, 'dashboard', 'normal');
    remove_meta_box($widget_id, 'dashboard', 'side');
  }
}, 999);

// Raise list-table pagination for post type index screens.
// This mirrors the old behavior of showing up to 100 items per page in the
// admin list views for posts, pages, and custom post types.
add_action('current_screen', function ($screen) {
  if (! $screen || $screen->base !== 'edit' || empty($screen->post_type)) {
    return;
  }
  add_filter('edit_posts_per_page', function () {
    return 100;
  });
  add_filter('edit_pages_per_page', function () {
    return 100;
  });
  add_filter("edit_{$screen->post_type}_per_page", function () {
    return 100;
  });
});

/**
 * Hide selected sections on user profile screens.
 *
 * This helper is shared by both profile hooks above. It can target only
 * certain roles and then hides sections in two ways:
 * - removes all custom contact methods when requested
 * - injects small CSS rules for built-in profile field groups
 *
 * @param array $groups Section groups to hide. Supported values: options,
 *                      names, contact.
 * @param array $roles  Limit the behavior to these user roles. Empty means all.
 */
function hide_user_profile_sections(array $groups, array $roles = []) {
  // On user-edit screens WordPress passes the target user through `user_id`.
  // On self-profile screens we fall back to the current logged-in user.
  $user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : get_current_user_id();
  $user = get_userdata($user_id);

  // Bail if WordPress could not resolve a real user object.
  if (! $user instanceof \WP_User) {
    return;
  }

  // When a role allow-list is provided, do nothing for all other roles.
  if ($roles && ! array_intersect($roles, $user->roles)) {
    return;
  }

  // Remove contact-method rows entirely instead of only hiding them with CSS.
  if (in_array('contact', $groups, true)) {
    add_filter('user_contactmethods', '__return_empty_array');
  }

  $selectors = [];

  // Name-related fields kept out of the profile editor for the selected roles.
  if (in_array('names', $groups, true)) {
    $selectors = array_merge($selectors, [
      '.user-first-name-wrap',
      '.user-last-name-wrap',
      '.user-nickname-wrap',
      '.user-display-name-wrap',
    ]);
  }

  // Personal preference controls that are not useful for this editorial setup.
  if (in_array('options', $groups, true)) {
    $selectors = array_merge($selectors, [
      '.user-rich-editing-wrap',
      '.user-syntax-highlighting-wrap',
      '.user-admin-color-wrap',
      '.user-comment-shortcuts-wrap',
      '.show-admin-bar',
      '.user-language-wrap',
    ]);
  }

  if (empty($selectors)) {
    return;
  }

  // Inject minimal CSS rather than restructuring profile templates.
  // This keeps the tweak small and avoids deeper admin overrides.
  echo '<style>' . esc_html(implode(',', $selectors)) . '{display:none!important;}</style>';
}
