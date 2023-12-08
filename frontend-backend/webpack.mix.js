const mix = require("laravel-mix");
const ImageminPlugin = require("imagemin-webpack-plugin").default;
const CopyWebpackPlugin = require("copy-webpack-plugin");
const imageminMozjpeg = require("imagemin-mozjpeg");

/**
 * Para habilitar o BROWSERSYNC com reload automático sempre que uma mudança for feita em seu código,
 * preencha a variável abaixo com o caminho de seu projeto em relação ao seu "localhost".
 *
 * Por exemplo se o seu site fica em "http://localhost/burnbase/beta" a variável abaixo deve ser preenchida como:
 * `const BROWSERSYNC_PROJECT_PATH = "/burnbase/beta";`
 *
 * Se fosse algo do tipo "https://localhost/megamix/trunk" a variável ficaria:
 * `const BROWSERSYNC_PROJECT_PATH = "/megamix/trunk";`
 *
 * Caso você não queira utilizar o BROWSERSYNC, deixe a variável abaixo preenchida com `null`:
 * `const BROWSERSYNC_PROJECT_PATH = null;`
 */
const BROWSERSYNC_PROJECT_PATH = null;

mix.webpackConfig({
  plugins: [
    new CopyWebpackPlugin([
      {
        from: "webroot/src/img",
        ignore: [".DS_Store"],
        to: "img",
      },
    ]),
    new ImageminPlugin({
      test: /\.(jpe?g|png|gif|svg)$/i,
      optipng: {
        optimizationLevel: mix.inProduction() ? 3 : 2,
      },
      plugins: [
        imageminMozjpeg({
          quality: 90,
        }),
      ],
    }),
  ],
});

mix
  .options({ processCssUrls: false })
  .js("webroot/src/js/default.js", "js")
  .js("webroot/src/admin/js/default.js", "js/admin")
  .postCss("webroot/src/css/default.css", "css")
  .postCss("webroot/src/admin/css/default.css", "css/admin")
  .postCss("webroot/src/admin/css/ckeditorstyles.css", "ckeditor/mystyles.css")
  .copyDirectory("webroot/src/fonts", "webroot/fonts")
  .setPublicPath("webroot")
  .disableNotifications();

if (mix.inProduction()) {
  mix.version();
} else if (!!BROWSERSYNC_PROJECT_PATH) {
  mix.browserSync({
    ui: false,
    open: !!BROWSERSYNC_PROJECT_PATH,
    https: false,
    injectChanges: false,
    reloadDelay: 2500,
    files: [
      "./Plugin/**/*.ctp",
      "./Plugin/**/*.php",
      "./View/**/*.ctp",
      "./View/**/*.php",
      "./webroot/src/js/**/*.{js,ts,jsx,tsx}",
      "./webroot/css/**/*.css",
    ],
    startPath: BROWSERSYNC_PROJECT_PATH,
    port: 8081,
    host: "0.0.0.0",
    proxy: {
      target: "http://localhost",
      ws: true,
    },
  });
}
