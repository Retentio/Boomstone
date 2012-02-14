Boomstone, a spark for PHP project.
===================================

Installation
------------

Vendors installations
```
# optionnal: chown u+x bin/vendors
bin/vendors install
```

Some chmods
```
mkdir cache cache/boomgo
chmod -R 777 cache src/Resources/locales
```

Export twitter bootstrap assets
```
mkdir web/js/bootstrap
cp vendor/bootstrap/js/*.js web/js/bootstrap/
cp -R vendor/bootstrap/img web/
```

Configuration
```
cp app/config.php.dist app/config.php
```

I18n console command
--------------------
```
php app/console
php app/console i18n:update
```
