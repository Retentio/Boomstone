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

use Boomstone\Form;
use Boomstone\Document\User,
    Boomstone\Document\PasswordRequest;
use Silex\Application,
    Silex\ControllerProviderInterface,
    Silex\ControllerCollection;
use Symfony\Component\Form\FormError;

/**
 * Sign controller provider.
 */
class Sign implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();

        /**
         * signup
         *
         * GET /sign/up
         * registration form
         */
        $controllers->get('/up', function () use ($app) {
            $form = $app['form.factory']->create(new Form\UserType());

            return $app['twig']->render('Sign/up.html.twig', array('form' => $form->createView()));
        })->bind('signup');

        /**
         * signup_create
         *
         * POST /sign/up
         * registration process
         */
        $controllers->post('/up', function () use ($app) {
            $form = $app['form.factory']->create(new Form\UserType());
            $form->bindRequest($app['request']);

            if ($form->isValid()) {
                $data = $form->getData();

                $user = $app['boomgo']->get('User')->findOneByEmail($data['email']);

                if (null === $user) {
                    // Save user
                    $user = new User();
                    $user->setEmail($data['email']);
                    $user->setPassword(\Boomstone\Utils\Toolbox::encode($data['password'], $user->getSalt()));
                    $app['boomgo']->get('User')->save($user);

                    // Welcome Email
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Come back to retentio !')
                        ->setFrom($app['mailer.sender'])
                        ->setTo(array($user->getEmail()))
                        ->setBody('Welcome to Retentio !');
                    $app['mailer']->send($message);

                    // Flash message & redirect
                    $app['session']->setFlash('success', $app['translator']->trans('Successfully registered', array(), 'flash'));
                    return $app->redirect($app['url_generator']->generate('signin'));
                } else {
                    // User is not unique, add error.
                    $form->get('email')->addError(new FormError($app['translator']->trans('An account already exists with this email', array(), 'form')));
                }
            }

            return $app['twig']->render('Sign/up.html.twig', array('form' => $form->createView()));
        })->bind('signup_create');

        /**
         * signin
         *
         * GET /sign/in
         * login form
         */
        $controllers->get('/in', function () use ($app) {
            $defaults = ($app['session']->hasFlash('forgotten.signup')) ? array('email' => $app['session']->getFlash('forgotten.signup')) : false;

            $form = $app['form.factory']->create(new Form\UserType(), $defaults);

            return $app['twig']->render('Sign/in.html.twig', array('form' => $form->createView(), 'forgotten_signup' => $defaults));
        })->bind('signin');

        /**
         * signin_check
         *
         * POST /sign/in
         * login process
         */
        $controllers->post('/in', function () use ($app) {
            $form = $app['form.factory']->create(new Form\UserType());
            $form->bindRequest($app['request']);

            if ($form->isValid()) {
                $data = $form->getData();

                $user = $app['boomgo']->get('User')->findOneByEmail($data['email']);

                if (null !== $user) {
                    if ($app['boomgo']->get('User')->authenticate($user, $data['password'])) {
                        $app['session']->set('user', $user);

                         $app['session']->setFlash('success', $app['translator']->trans('Successfully signed in', array(), 'flash'));
                        return $app->redirect($app['url_generator']->generate('account'));
                    } else {
                        $form->addError(new FormError($app['translator']->trans('Sorry, unknown email or invalid password', array(), 'form')));
                    }
                } else {
                    $form->addError(new FormError($app['translator']->trans('Sorry, unknown email or invalid password', array(), 'form')));
                }
            }

            return $app['twig']->render('Sign/in.html.twig', array('form' => $form->createView()));
        })->bind('signin_check');

        /**
         * signout
         *
         * GET /sign/out
         * logout process
         */
        $controllers->get('/out', function () use ($app) {
            $app['session']->clear();
            $app['session']->setFlash('success', $app['translator']->trans('Successfully logged out', array(), 'flash'));
            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('signout');

        return $controllers;
    }
}