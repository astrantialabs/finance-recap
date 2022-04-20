const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/scss/base.scss', 'public/css');