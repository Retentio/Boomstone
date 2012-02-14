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

use Boomstone\Command\ApplicationAwareCommand;

use Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Application,
    Symfony\Component\Console\Helper\HelperSet;

use Symfony\Component\Translation\MessageCatalogue,
    Symfony\Component\Translation\Loader\XliffFileLoader,
    Symfony\Component\Translation\Dumper\XliffFileDumper,
    Symfony\Component\Translation\Writer\TranslationWriter;


use Symfony\Bridge\Twig\Translation\TwigExtractor,
    Symfony\Bridge\Twig\Extension\TranslationExtension;

/**
 * I18n Command
 *
 * Extract translated string from twig template.
 */
class I18nCommand extends ApplicationAwareCommand
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->setDescription('Translation update command');
        $this->setHelp('i18n:update will extract the translated messages from any template');
        $this->addArgument('locale', InputArgument::OPTIONAL, 'Define the locale for the catalogue to build (fr, en, it...)', 'en');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootDir = $this->getApplication()->getRootDirectory();

        $app = $this->getSilexApplication();
        $twig = $app['twig'];
        $translator = $app['translator'];

        $output->writeln('Extract internationlized string in twig templates located in "<comment>/src/Resources/views</comment>"');

        $newCatalogue = new MessageCatalogue($input->getArgument('locale'));
        $extractor  = new TwigExtractor($twig);
        $extractor->extract($rootDir.'/src/Resources/views', $newCatalogue);

        $output->writeln('Load existing translation file located in "<comment>/src/Resources/locales</comment>"');

        $app['translator']->loadCatalogue($input->getArgument('locale'));
        $oldCatalogue = $app['translator']->getCatalogues();

        $oldTranslations = $oldCatalogue[$input->getArgument('locale')]->all();
        $newTranslations = $newCatalogue->all();
        $mergedTranslations = array_replace_recursive($newTranslations, $oldTranslations);

        $catalogue = new MessageCatalogue($input->getArgument('locale'), $mergedTranslations);

        $output->writeln('Write updates to the translation file');

        $dumper = $app['translator.dumper'];
        $writer = new TranslationWriter();
        $writer->addDumper('xliff', $dumper);
        $writer->writeTranslations($catalogue, 'xliff',  array('path' => $app['translation.file_path'].$input->getArgument('locale')));

        $output->writeln(sprintf('<info>Translation file for the locale %s Successfully updated !</info>', $input->getArgument('locale')));
    }
}