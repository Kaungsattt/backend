import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const backendRoot = dirname(fileURLToPath(import.meta.url));
const frontendRoot = resolve(backendRoot, '..', 'frontend');

export default defineConfig({
    root: frontendRoot,
    plugins: [react(), tailwindcss()],
    resolve: {
        alias: {
            react: resolve(backendRoot, 'node_modules/react'),
            'react-dom': resolve(backendRoot, 'node_modules/react-dom'),
        },
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        fs: {
            allow: [backendRoot, frontendRoot],
        },
        proxy: {
            '/api': {
                target: 'http://127.0.0.1:8002',
                changeOrigin: true,
            },
        },
    },
});
