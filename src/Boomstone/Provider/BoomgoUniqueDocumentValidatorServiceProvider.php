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

use Retentio\Validator\Constraints\BoomgoUniqueValidator;

use Silex\Application,
    Silex\ServiceProviderInterface;

/**
 * Boomgo Service Provider.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class BoomgoUniqueDocumentValidatorServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['boomgo.validator.unique'] = $app->share(function () use ($app) {
            return new BoomgoUniqueValidator($app['boomgo']);
        });
    }
}