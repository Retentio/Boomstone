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

use Boomgo\Provider\RepositoryProvider;

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

            $mapperProviderCacheClass =  'Boomgo\\Cache\\'.$app['boomgo.mapper.cache'];
            $mapperProviderCache = new $mapperProviderCacheClass;

            $mapperProviderClass = 'Boomgo\\Provider\\MapperProvider';
            $mapperProvider = new $mapperProviderClass($app['boomgo.mapper.namespace'], $app['boomgo.document.namespace'], $mapperProviderCache);

            $repositoryProviderCacheClass =  'Boomgo\\Cache\\'.$app['boomgo.repository.cache'];
            $repositoryProviderCache = new $repositoryProviderCacheClass;

            return new RepositoryProvider($app['boomgo.repository.namespace'], $app['boomgo.document.namespace'], $repositoryProviderCache, $app['mongodb'], $mapperProvider);
        });
    }
}