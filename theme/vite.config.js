import { fileURLToPath, URL } from 'node:url';
import postcssImport from 'postcss-import';
import postcssPresetEnv from 'postcss-preset-env';
import postcssPxToRem from 'postcss-pxtorem';
import { defineConfig, transformWithOxc } from 'vite';

const entryPoints = {
  head: 'app/head.js',
  body: 'app/body.js',
  blocks: 'app/blocks.js',
  editor: 'app/editor.js',
  admin: 'config/admin.js',
};

function wordpressJsx() {
  return {
    name: 'wordpress-jsx',
    enforce: 'pre',
    async transform(code, id) {
      if (!id.includes('/app/') || !id.endsWith('.js')) {
        return null;
      }

      return transformWithOxc(code, id, {
        lang: 'jsx',
        jsx: {
          runtime: 'classic',
          pragma: 'wp.element.createElement',
          pragmaFrag: 'wp.element.Fragment',
        },
      });
    },
  };
}

function wordpressManifest() {
  return {
    name: 'wordpress-manifest',
    generateBundle(_options, bundle) {
      const manifest = {};

      const collectImportedCss = (chunk, seen = new Set()) => {
        if (!chunk || seen.has(chunk.fileName)) {
          return [];
        }

        seen.add(chunk.fileName);

        const localCss = chunk.viteMetadata?.importedCss
          ? Array.from(chunk.viteMetadata.importedCss).map((file) => `/dist/${file}`)
          : [];

        const importedCss = (chunk.imports || []).flatMap((fileName) => {
          const importedChunk = bundle[fileName];
          return importedChunk?.type === 'chunk' ? collectImportedCss(importedChunk, seen) : [];
        });

        return Array.from(new Set([...localCss, ...importedCss]));
      };

      Object.entries(bundle).forEach(([, asset]) => {
        if (asset.type !== 'chunk' || !asset.isEntry || !(asset.name in entryPoints)) {
          return;
        }

        const cssFiles = collectImportedCss(asset);

        manifest[asset.name] = {};

        if (cssFiles.length === 1) {
          manifest[asset.name].css = cssFiles[0];
        } else if (cssFiles.length > 1) {
          manifest[asset.name].css = cssFiles;
        }

        manifest[asset.name].js = `/dist/${asset.fileName}`;
      });

      this.emitFile({
        type: 'asset',
        fileName: 'manifest.json',
        source: JSON.stringify(manifest),
      });
    },
  };
}

export default defineConfig({
  base: '/dist/',
  publicDir: false,
  resolve: {
    alias: {
      '@lib': fileURLToPath(new URL('./lib/index.js', import.meta.url)),
    },
  },
  plugins: [wordpressJsx(), wordpressManifest()],
  build: {
    outDir: 'dist',
    assetsDir: '.',
    emptyOutDir: true,
    manifest: false,
    rolldownOptions: {
      input: entryPoints,
      output: {
        entryFileNames: '[name]-[hash].js',
        chunkFileNames: 'chunks/[name]-[hash].js',
        assetFileNames: '[name]-[hash][extname]',
      },
    },
  },
  css: {
    postcss: {
      plugins: [
        postcssImport(),
        postcssPresetEnv({ stage: 2, features: { 'nesting-rules': true } }),
        postcssPxToRem({ propList: ['*'] }),
      ],
    },
  },
});
