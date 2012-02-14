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

use Boomstone\Translation\Translator;
use Silex\Application,
    Silex\ServiceProviderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\MessageSelector;



/**
 * I18n Service Provider.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class I18nServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $supportedFormats = array(
            'csv'   => array('dumper' => 'CsvFileDumper',   'loader' => 'CsvFileLoader',   'ext' => 'csv'),
            'ini'   => array('dumper' => 'IniFileDumper',   'loader' => 'IniFileLoader',   'ext' => 'ini'),
            'mo'    => array('dumper' => 'MoFileDumper' ,   'loader' => 'MoFileLoader',    'ext' => 'mo'),
            'php'   => array('dumper' => 'PhpFileDumper',   'loader' => 'PhpFileLoader',   'ext' => 'php'),
            'po'    => array('dumper' => 'PoFileDumper',    'loader' => 'PoFileLoader',    'ext' => 'po'),
            'qt'    => array('dumper' => 'QtFileDumper',    'loader' => 'QtTranslationsLoader', 'ext' => 'ts'),
            'xliff' => array('dumper' => 'XliffFileDumper', 'loader' => 'XliffFileLoader', 'ext' => 'xlf'),
            'yaml'  => array('dumper' => 'YamlFileDumper',  'loader' => 'YamlFileLoader',  'ext' => 'yml')
        );

        if (!isset($supportedFormats[$app['translation.format']])) {
            throw new \RuntimeException(sprintf('The translation format "%s" is not yet supported, contribute on github !',$app['translation.format']));
        }

        $format = $app['translation.format'];

        $app['translator'] = $app->share(function () use ($app, $format, $supportedFormats) {
            $translator = new Translator(isset($app['locale']) ? $app['locale'] : 'en', $app['translator.message_selector']);

            if (isset($app['locale_fallback'])) {
                $translator->setFallbackLocale($app['locale_fallback']);
            }

            $translator->addLoader($format, $app['translator.loader']);

            $finder = new Finder();
            $extension = $supportedFormats[$format]['ext'];

            $files = $finder->files()->name('*.'.$extension)->in($app['translation.file_path']);
            foreach($files as $file) {
                $locale = $file->getRelativePath();
                $filepath = $file->getPathname();
                $filename = $file->getFilename();
                $domain = str_replace('.'.$locale.'.'.$extension, '', $filename);
                $translator->addResource($format, $filepath, $locale, $domain);
            }

            return $translator;
        });

        $app['translator.loader'] = $app->share(function () use ($app, $format, $supportedFormats) {
            $loaderNs = 'Symfony\\Component\\Translation\\Loader';
            $loaderClass = $loaderNs.'\\'.$supportedFormats[$format]['loader'];
            return new $loaderClass;
        });

        $app['translator.dumper'] = $app->share(function() use ($app, $format, $supportedFormats) {
            $dumperNs = 'Symfony\\Component\\Translation\\Dumper';
            $dumperClass = $dumperNs.'\\'.$supportedFormats[$format]['dumper'];
            return new $dumperClass;
        });

        $app['translator.message_selector'] = $app->share(function () {
            return new MessageSelector();
        });

        if (isset($app['translation.class_path'])) {
            $app['autoloader']->registerNamespace('Symfony\\Component\\Translation', $app['translation.class_path']);
        }
    }
}
