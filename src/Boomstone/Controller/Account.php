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

namespace Boomstone\Controller;

use Silex\ControllerProviderInterface,
    Silex\ControllerCollection,
    Silex\Application;
use Symfony\Component\Form\FormError;
use Boomstone\Form;
use Boomstone\Document\User;

/**
 * Account controller provider.
 */
class Account implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();

        /**
         * account
         *
         * GET /account
         * User account
         */
        $controllers->get('/', function () use ($app) {
            return $app['twig']->render('Account/home.html.twig');
        })
        ->bind('account')
        ->value('security', array('ROLE_MEMBER'));

        return $controllers;
    }
}