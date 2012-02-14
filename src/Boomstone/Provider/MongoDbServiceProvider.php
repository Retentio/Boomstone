<?php

/**
 * This file is part of the Boomstone PHP Silex boilerplate.
 *
 * https://github.com/Retentio/Boomstone
 *
 * (c) Ludovic Fleury <ludo.fleury@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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