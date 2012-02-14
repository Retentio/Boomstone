<?php

namespace Boomstone\Provider;

use Boomgo;
use Silex\Application,
    Silex\ServiceProviderInterface;

/**
 * Repository Provider.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class MongoDbServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongodb'] = $app->share(function () use ($app) {
            $default = array();
            $options = (isset($app['mongodb.options'])) ? array_merge($default, $app['mongodb.options']): $default;
            return new \Mongo(implode($app['mongodb.servers'],','), $options);
        });
    }
}