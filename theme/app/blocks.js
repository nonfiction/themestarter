// This is contains JavaScript for blocks (shared by front-end and editor)
// WP hook: enqueue_block_assets (in footer)

// Import block-related script.js files
// app/blocks/*/script.js
// app/*/blocks/*/script.js
Object.values(import.meta.glob("./blocks/*/script.js", { eager: true }));
Object.values(import.meta.glob("./*/blocks/*/script.js", { eager: true }));

// Custom scripting below
//
