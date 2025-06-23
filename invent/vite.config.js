import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js'
      ],
      refresh: [
        {
          paths: ['resources/views/**'],
          config: false,
        },
      ],
    }),
    tailwindcss({
      config: './tailwind.config.js',
    }),
  ],
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
  build: {
    manifest: true,
    outDir: 'public/build',
    rollupOptions: {
      output: {
        entryFileNames: `[name].js`,
      chunkFileNames: `chunks/[name]-[hash].js`,
      assetFileNames: `assets/[name]-[hash].[ext]`
      }
    }
  },
});