// This is contains JavaScript for the theme that loads in the <head>
// WP hook: wp_enqueue_scripts

// Import primary styles
import "./views/css/style.css";

// Import post type-related script.js files
// app/*/script.js
Object.values(import.meta.glob("./*/script.js", { eager: true }));

// Custom scripting below
//
