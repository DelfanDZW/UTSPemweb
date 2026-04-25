import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      // Mengarahkan semua request '/api' ke server Laragon (PHP)
      '/api': {
        // Menggunakan PHP Built-in Server
        target: 'http://localhost:8000', 
        changeOrigin: true,
      }
    }
  }
})
