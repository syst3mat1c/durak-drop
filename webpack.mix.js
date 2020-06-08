const mix = require('laravel-mix');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const imageminMozjpeg = require('imagemin-mozjpeg');

require('laravel-mix-imagemin');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .extract(['lodash', 'popper.js', 'vue', 'noty', 'socket.io-client', 'jquery'])
    .sass('resources/sass/app.scss', 'public/css')
    .version();

mix.minify('public/js/app.js')
    .minify('public/js/vendor.js');

mix.webpackConfig({
    plugins: [
        new CopyWebpackPlugin([{
            from: 'resources/default/images',
            to: 'default/images' // Laravel mix will place this in 'public/img'
        }]),
        new ImageminPlugin({
            test: /\.(jpe?g|png|gif|svg)$/i,
            plugins: [
                imageminMozjpeg({
                    quality: 80
                })
            ]
        })
    ]
});

module.exports = {
    mode: 'production'
};
