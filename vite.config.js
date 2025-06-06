import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin/categories/index.js',
                'resources/js/admin/posts/index.js',
                'resources/js/admin/posts/create.js',
                'resources/js/admin/posts/edit.js',
                'resources/js/admin/tags/index.js',
            ],
            refresh: true,
        }),
    ],
});
