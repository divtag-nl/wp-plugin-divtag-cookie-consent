import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    rollupOptions: {
      input: {
        admin: 'src/admin/cookie-consent-admin.js',
        client: 'src/client/cookie-consent-client.js',
      },
      output: {
        dir: 'dist',
        entryFileNames: 'js/cookie-consent-[name].js',
        assetFileNames: 'css/cookie-consent-[name].[ext]'
      }
    },
  }
})