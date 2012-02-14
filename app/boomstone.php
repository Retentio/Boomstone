<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->before(function() use ($app) {
    // I18n
    $app['locale'] = ($app['session']->get('locale')) ? $app['session']->get('locale') : $app['locale_fallback'];


    // Security
    $authorization = $app['request']->get('security');
    if ($authorization) {
        if (!$app['session']->get('user')) {
            // Basic Authentification
            $app['session']->setFlash('warning', $app['translator']->trans('Private area, you must be authenticated', array(), 'flash'));

            return $app->redirect($app['url_generator']->generate('signin'));
        } else {
            // Credential Authorization
            if (is_string($authorization)) {
                $authorization = array($authorization);
            }
            foreach ($authorization as $role) {
                if (!$app['session']->get('user')->hasRole($role)) {
                    $app['session']->setFlash('error', $app['translator']->trans('Restricted area, you don\'t have enough authorization', array(), 'flash'));
                    return $app->redirect($app['url_generator']->generate('homepage'));
                }
            }
        }
    }
});

/**
 * homepage
 *
 * GET /
 */
$app->get('/', function () use ($app) {
    return $app['twig']->render('homepage.html.twig');
})->bind('homepage');

/**
 * localize
 *
 * GET /localize/{locale}
 */
$app->get('/localize/{locale}', function ($locale) use ($app) {
    if (in_array($locale,array('en','fr'))) {
        $app['session']->set('locale', $locale);
        $app['session']->setFlash('info', $app['translator']->trans('Version changed', array(), 'flash'));
    } else {
        $app['session']->setFlash('warning', $app['translator']->trans('Sorry, we do not suppord this language for the moment', array(), 'flash'));
    }
    return $app->redirect($app['url_generator']->generate('homepage'));
})->bind('localize');

$app->mount('/sign', new \Boomstone\Controller\Sign());
$app->mount('/lost', new \Boomstone\Controller\Lost());
$app->mount('/account', new \Boomstone\Controller\Account());
return $app;