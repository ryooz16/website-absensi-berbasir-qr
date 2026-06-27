import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/qr-scanner.js",
                "resources/js/chart-init.js",
            ],
            refresh: true,
        }),
    ],
    server: {
        host: "127.0.0.1",
        port: 5173,
    },
    // server: {
    //     host: true,
    //     port: 5173, // Optional: change the default port if needed
    //     allowedHosts: [
    //         ".ngrok-free.app", // This allows any domain ending with .ngrok-free.app
    //     ],
    // },
});
