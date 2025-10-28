#!/usr/bin/env node
const { build } = require('esbuild');
const { sassPlugin } = require('esbuild-sass-plugin');

const watch = process.argv.includes('--watch');

(async () => {
  try {
    await build({
      entryPoints: ['resources/scss/app.scss', 'resources/ts/app.ts'],
      bundle: true,
      outdir: 'public/assets',
      sourcemap: watch,
      minify: !watch,
      plugins: [sassPlugin({ type: 'css' })],
      loader: {
        '.woff': 'file',
        '.woff2': 'file',
        '.svg': 'file'
      },
      metafile: true,
      logLevel: 'info',
      watch: watch && {
        onRebuild(error) {
          if (error) {
            console.error('Rebuild failed:', error);
          } else {
            console.log('Rebuild succeeded');
          }
        }
      }
    });
  } catch (error) {
    console.error(error);
    process.exit(1);
  }
})();
