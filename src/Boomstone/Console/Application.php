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

namespace Boomstone\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Silex\Application as SilexApplication;

/**
 * Application.
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class Application extends BaseApplication
{
    private $silexApplication;

    private $rootDirectory;

    /**
     * Constructor.
     *
     * @param string             $name              The name of the application
     * @param string             $version           The version of the application
     * @param Silex\Appplication $silexApplication
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN', SilexApplication $silexApplication)
    {
        parent::__construct($name, $version);
        $this->silexApplication = $silexApplication;
        $this->rootDirectory = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..';
    }

    /**
     * Return the current silex application
     *
     * @return Silex\Application
     */
    public function getSilexApplication()
    {
        return $this->silexApplication;
    }

    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }
}