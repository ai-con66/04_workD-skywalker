import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath } from 'node:url'
import { dirname, resolve } from 'node:path'

const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)

export default defineConfig(({ command }) => {
  return {
    // 開発時はルート、ビルド時はSkyWalkerパスへ
    base: command === 'build' ? '/SkyWalker/' : '/',

    root: './',

    plugins: [
      vue(),
      {
        name: 'watch-php-and-html',
        configureServer(server) {
          server.watcher.add(resolve(__dirname, 'public/contact/**/*.php'));
        },
        handleHotUpdate({ file, server }) {
          if (file.endsWith('.php') || file.endsWith('.html')) {
            server.ws.send({ type: 'full-reload' });
          }
        },
      }
    ],
    
    server: {
      port: 7004,
      open: true,
      host: true,
      watch: {
       usePolling: true,
       interval: 100,
      },
      proxy: {
        '^/.*\\.php': {
          target: 'http://localhost:8004',
          changeOrigin: true,
        },
      },
    },

    build: {
      outDir: resolve(__dirname, '../74_build_results'),
      emptyOutDir: true, 
      rollupOptions: {
        input: {
          index: resolve(__dirname, 'index.html'),
          privacy: resolve(__dirname, 'html/privacy.html'),
        },
      },
    },
  }
})