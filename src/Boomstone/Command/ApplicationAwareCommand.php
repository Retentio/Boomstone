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

namespace Boomstone\Command;

use Boomstone\Console\Application;
use Symfony\Component\Console\Command\Command;

/**
 * Application aware command
 *
 * Provide a silex application in CLI context.
 */
abstract class ApplicationAwareCommand extends Command
{
    /**
     * Return the current silex application
     *
     * @return Silex\Application
     */
    public function getSilexApplication()
    {
        return $this->getApplication()->getSilexApplication();
    }
}