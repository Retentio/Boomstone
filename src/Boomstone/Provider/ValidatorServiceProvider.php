<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Boomstone\Provider;

use Boomstone\Validator\ConstraintValidatorFactory;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Mapping\ClassMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;

/**
 * Symfony Validator component alternative Provider.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class ValidatorServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['validator'] = $app->share(function () use ($app) {
            return new Validator(
                $app['validator.mapping.metadata_factory'],
                $app['validator.validator_factory']
            );
        });

        $app['validator.mapping.metadata_factory'] = $app->share(function () use ($app) {
            $class = $app['validator.mapping.metadata_factory_class'];


            return new $class($app['validator.mapping.loader'], $app['validator.mapping.cache']);
        });

        $app['validator.mapping.loader'] = $app->share(function () use ($app) {
            $class = $app['validator.mapping.loader_class'];

            if (isset($app['validator.mapping.path'])) {
                $finder = new  \Symfony\Component\Finder\Finder();
                $files = $finder->files()->name('*.yml')->in($app['validator.mapping.path']);

                $collection = array();
                foreach ($files as $file) {
                    $collection[] = realpath($file->getPathName());
                }

                $loader = new $class($collection);
            } else {
                $loader = new $class();
            }

            return $loader;
        });

        $app['validator.mapping.cache'] = $app->share(function () use ($app) {
            $cacheClass = isset($app['validator.mapping.cache_class']) ? $app['validator.mapping.cache_class'] : null;
            $cachePrefix = isset($app['validator.mapping.cache_prefix']) ? $app['validator.mapping.cache_prefix'] : null;

            return (isset($cacheClass)) ? new $cacheClass($cachePrefix) : null;
        });

        $app['validator.validator_factory'] = $app->share(function () use ($app) {
            $services = (isset($app['validator.services'])) ? $app['validator.services'] : null;

            return new ConstraintValidatorFactory($app, $services);
        });

        if (isset($app['validator.class_path'])) {
            $app['autoloader']->registerNamespace('Symfony\\Component\\Validator', $app['validator.class_path']);
        }
    }
}
