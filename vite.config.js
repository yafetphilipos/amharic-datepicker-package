import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/datepicker.css',
                'resources/js/datepicker.js',
                'resources/js/ethiopian-calendar.js',
            ],
            refresh: true,
        }),
    ],
});
