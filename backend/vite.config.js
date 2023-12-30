import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  server: {
    host: '192.168.56.10',
    // port: '5175',
    watch: {
      usePolling: true,
    },
  },
  plugins: [vue()]
})
