import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      // Mengarahkan semua request '/api' ke server Laragon (PHP)
      '/api': {
        // Karena Laragon document root ada di folder ini langsung
        target: 'http://localhost', 
        changeOrigin: true,
      }
    }
  }
})
