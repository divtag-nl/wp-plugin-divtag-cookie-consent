let mix = require('laravel-mix');

// Client
mix.js('src/client/cookie-consent-client.js', 'js')
   .css('src/client/cookie-consent-client.css', 'css')
   .version()
   .setPublicPath('dist');

// Admin
mix.js('src/admin/cookie-consent-admin.js', 'js')
   .css('src/admin/cookie-consent-admin.css', 'css')
   .version()
   .setPublicPath('dist');
