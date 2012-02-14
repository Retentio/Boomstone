<?php

namespace Boomstone\Command;

use Boomstone\Console\Application;
use Symfony\Component\Console\Command\Command;

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