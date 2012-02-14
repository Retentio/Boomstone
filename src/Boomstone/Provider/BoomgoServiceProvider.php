<?php

namespace Boomstone\Provider;

use Boomgo;
use Silex\Application,
    Silex\ServiceProviderInterface;

/**
 * Boomgo Service Provider.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class BoomgoServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['boomgo'] = $app->share(function () use ($app) {

            $parserClass = '\\Boomgo\\Parser\\'.$app['boomgo.parser'];

            $formatterClass = '\\Boomgo\\Formatter\\'.$app['boomgo.formatter'];

            $cacheClass = '\\Boomgo\\Cache\\'.$app['boomgo.cache'];
            $cacheOptions = (isset($app['boomgo.cache.options'])) ? implode($app['boomgo.cache.options'],',') : null;

            $mapperClass = '\\Boomgo\\Mapper\\'.$app['boomgo.mapper'];

            $cache = new $cacheClass($cacheOptions);
            $formatter = new $formatterClass($formatterOptions);
            $parser= new $parserClass($formatter);

            $mapper = new $mapperClass($parser, $cache);
            return new Boomgo\Manager($app['mongodb'], $mapper, $app['mongodb.options']['db'], $app['boomgo.repositories']);
        });
    }
}