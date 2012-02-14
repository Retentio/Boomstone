<?php

require_once __DIR__.'/../vendor/silex/autoload.php';

$app = new Silex\Application();

$app['autoloader']->registerNamespaces(array(
    'Boomstone'      => __DIR__.'/../src',
    'Symfony'       => __DIR__.'/../vendor',
    'Boomgo'        => __DIR__.'/../vendor/boomgo/src'
));

// Include configuration
if (!file_exists(__DIR__.'/config.php')) {
    throw new RuntimeException('You must create your own configuration file ("src/config.php"). See "src/config.php.dist" for an example config file.');
}
require_once __DIR__.'/config.php';
// require_once __DIR__.'/config_dev.php';
// require_once __DIR__.'/config_test.php';

/** Silex Extensions */
use Silex\Provider\SymfonyBridgesServiceProvider,
    Silex\Provider\UrlGeneratorServiceProvider,
    Silex\Provider\SessionServiceProvider,
    Silex\Provider\FormServiceProvider,
    Silex\Provider\ValidatorServiceProvider,
    Silex\Provider\TranslationServiceProvider,
    Silex\Provider\SwiftmailerServiceProvider,
    Silex\Provider\TwigServiceProvider;
use Boomstone\Provider\BoomgoServiceProvider,
    Boomstone\Provider\MongoDBServiceProvider,
    Boomstone\Provider\I18nServiceProvider;

$app->register(new SymfonyBridgesServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new I18nServiceProvider(), $config['i18n']);
$app->register(new MongoDbServiceProvider(), $config['mongodb']);
$app->register(new BoomgoServiceProvider(), $config['boomgo']);
$app->register(new SessionServiceProvider(), $config['session']);
$app->register(new TwigServiceProvider(), $config['twig']);
$app->register(new SwiftmailerServiceProvider(), $config['swiftmailer']);

return $app;