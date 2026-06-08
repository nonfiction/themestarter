// This is contains JavaScript for blocktypes (editor)
// WP hook: enqueue_block_editor_assets
import "./views/css/editor-ui.css";

// Import block-related index.js files
// app/blocks/*/index.js
// app/*/blocks/*/index.js
// app/views/*/editor.js
// app/*/editor.js
Object.values(import.meta.glob("./blocks/*/index.js", { eager: true }));
Object.values(import.meta.glob("./*/blocks/*/index.js", { eager: true }));
Object.values(import.meta.glob("./views/*/editor.js", { eager: true }));
Object.values(import.meta.glob("./*/editor.js", { eager: true }));

// Custom scripting below
//
