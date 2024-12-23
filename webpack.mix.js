const mix = require('laravel-mix');

mix.webpackConfig({
    plugins: [
        // Set the NODE_ENV environment variable to 'production'
        new (require('webpack').DefinePlugin)({
            'process.env': {
                NODE_ENV: JSON.stringify('production')
            }
        }),
    ]
});

mix.disableNotifications(); // This will disable the pop-up notifications.

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ]);
