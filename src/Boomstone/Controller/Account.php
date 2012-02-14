<?php

namespace Boomstone\Controller;

use Silex\ControllerProviderInterface,
    Silex\ControllerCollection,
    Silex\Application;
use Symfony\Component\Form\FormError;
use Boomstone\Form;
use Boomstone\Document\User\User;

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