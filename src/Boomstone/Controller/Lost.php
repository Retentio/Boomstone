<?php

namespace Boomstone\Controller;

use Silex\ControllerProviderInterface,
    Silex\ControllerCollection,
    Silex\Application;
use Symfony\Component\Form\FormError;
use Boomstone\Form;
use Boomstone\Document\User\User,
    Boomstone\Document\User\PasswordRequest;

class Lost implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();

        /**
         * lost_access
         *
         * GET /lost/request
         * Lost password form
         */
        $controllers->get('/request', function () use ($app) {
            $form = $app['form.factory']->create(new Form\PasswordRequestType());
            return $app['twig']->render('Lost/request.html.twig', array('form' => $form->createView()));
        })->bind('lost_access');

        /**
         * access_request
         *
         * POST /lost/request
         * Lost password process
         */
        $controllers->post('/request', function () use ($app) {
            $form = $app['form.factory']->create(new Form\PasswordRequestType());
            $form->bindRequest($app['request']);

            if ($form->isValid()) {
                $data = $form->getData();

                $user = $app['boomgo']->getRepository('User')->findOneByEmail($data['email']);

                if (null !== $user) {
                    $passwordRequest = new PasswordRequest();
                    $user->setPasswordRequest($passwordRequest);
                    $app['boomgo']->getRepository('User')->save($user);

                    $recoverLink = $app['url_generator']->generate('password_recovery', array('token' => $passwordRequest->getToken()),true);

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Retentio to the rescue')
                        ->setFrom($app['mailer.sender'])
                        ->setTo(array($user->getEmail()))
                        ->setBody('You seems to have lost your password, click here to recover: '.$recoverLink);
                    $app['mailer']->send($message);

                    $app['session']->setFlash('success', $app['translator']->trans('An email has been sent with the access recovery instructions', array(), 'flash'));

                    return $app->redirect($app['url_generator']->generate('homepage'));
                }
            }
            return $app['twig']->render('Lost/request.html.twig', array('form' => $form->createView()));
        })->bind('access_request');

        /**
         * access_recovery
         *
         * Get /lost/recovery/{token}
         * Access recovery form
         */
        $controllers->get('/recovery/{token}', function($token) use ($app) {
            $user = $app['boomgo']->getRepository('User')->findOneByRecoveryToken($token);

            if (null === $user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $form = $app['form.factory']->create(new Form\PasswordRecoveryType());

            return $app['twig']->render('Lost/recovery.html.twig', array('form' => $form->createView()));
        })->bind('access_recovery');

        /**
         * password_recover
         *
         * POST /lost/recovery/{token}
         * Access recovery process
         */
        $controllers->post('/recovery/{token}', function($token) use ($app) {
            $user = $app['boomgo']->getRepository('User')->findOneByRecoveryToken($token);

            if (null === $user) {
                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $form = $app['form.factory']->create(new Form\PasswordRecoveryType());
            $form->bindRequest($app['request']);

            if ($form->isValid()) {
                $data = $form->getData();

                if ($data['email'] !== $user->getEmail()) {
                    $form->addError(new FormError($app['translator']->trans('Sorry, this email does not correspond to your account', array(), 'form')));
                }

                if ($data['password'] !== $data['password2']) {
                    $form->addError(new FormError($app['translator']->trans('Passwords must be similar', array(), 'form')));
                }

                if (!$form->hasErrors()) {
                    $user->resetPasswordRequest(\Boomstone\Utils\Toolbox::encode($data['password'], $user->getSalt()));
                    $app['boomgo']->getRepository('User')->save($user);
                    return $app->redirect($app['url_generator']->generate('sign_in'));
                }
            }

            return $app['twig']->render('Lost/recovery.html.twig');
        })->bind('access_recover');;

        return $controllers;
    }
}