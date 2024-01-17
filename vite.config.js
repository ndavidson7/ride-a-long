import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "node:path";
import { globSync } from "glob";
import { fileURLToPath } from "node:url";

/**
 * Sets all files in `resources/js` as entry points.
 *
 * @author Ihar Aliakseyenka
 * @see https://github.com/laravel/framework/discussions/44578#discussioncomment-4439000
 */
let js = Object.fromEntries(
    globSync("resources/js/*.js").map((file) => [
        // This removes `resources/js/` as well as the file extension from each file, so e.g.
        // resources/js/foo.js becomes foo
        path.relative(
            "resources/js",
            file.slice(0, file.length - path.extname(file).length)
        ),
        fileURLToPath(new URL(file, import.meta.url)),
    ])
);
js = Object.values(js);

// Same as above but for CSS files
let css = Object.fromEntries(
    globSync("resources/css/*.css").map((file) => [
        path.relative(
            "resources/css",
            file.slice(0, file.length - path.extname(file).length)
        ),
        fileURLToPath(new URL(file, import.meta.url)),
    ])
);
css = Object.values(css);

let input = [js, css];
input = [].concat(...input);

export default defineConfig({
    plugins: [
        laravel({
            input,
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "@css": path.resolve(__dirname, "resources/css"),
            "@modules": path.resolve(__dirname, "resources/js"),
        },
    },
});
