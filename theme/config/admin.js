// This is contains JavaScript for admin pages
// WP hook: admin_enqueue_scripts (in footer)
import "./admin.css";

if (typeof window.disable_comments == "undefined") {
  window.disable_comments = { disabled_blocks: "" };
}
