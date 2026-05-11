import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/app_site.css',
                'resources/js/app.js', 
                'resources/js/app_site.js',
                'resources/js/app_auth.js'
                ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
