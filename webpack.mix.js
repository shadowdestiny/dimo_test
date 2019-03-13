const mix = require("laravel-mix");
require("laravel-mix-purgecss");

mix.js("resources/js/app.js", "public/js")
    .extract(["vue"])
    .postCss("resources/css/app.css", "public/css")
    .postCss("resources/css/paginator.css", "public/css")
    .options({
        postCss: [
            require("postcss-import")(),
            require("tailwindcss")("tailwind.js"),
            require("postcss-nesting")()
        ]
    })
    .purgeCss();
mix.browserSync(process.env.APP_URL);

if (mix.inProduction()) {
   // mix.version();
}
mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.join(__dirname, "./resources/js"),
            img: path.join(__dirname, "./resources/images")
        }
    }
});
