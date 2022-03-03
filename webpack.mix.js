let mix = require('laravel-mix');

// Client
mix.js('src/client/app-client.js', 'js')
   .css('src/client/app-client.css', 'css')
   .version()
   .setPublicPath('dist');

// Admin
mix.js('src/admin/app-admin.js', 'js')
   .css('src/admin/app-admin.css', 'css')
   .version()
   .setPublicPath('dist');
