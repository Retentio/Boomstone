<?php

namespace Boomstone\Command;

use Boomstone\Command\ApplicationAwareCommand;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Translation\MessageCatalogue,
    Symfony\Component\Translation\Writer\TranslationWriter;


use Symfony\Bridge\Twig\Translation\TwigExtractor;

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

        $output->writeln(sprintf('Extract internationlized string in twig templates located in "<comment>/src/Resources/views</comment>"'));

        $newCatalogue = new MessageCatalogue($input->getArgument('locale'));
        $extractor  = new TwigExtractor($twig);
        $extractor->extract($rootDir.'/src/Resources/views', $newCatalogue);

        $output->writeln(sprintf('Load existing translation file located in "<comment>/src/Resources/locales</comment>"'));

        $app['translator']->loadCatalogue($input->getArgument('locale'));
        $oldCatalogue = $app['translator']->getCatalogues();

        $oldTranslations = $oldCatalogue[$input->getArgument('locale')]->all();
        $newTranslations = $newCatalogue->all();
        $mergedTranslations = array_replace_recursive($newTranslations, $oldTranslations);

        $catalogue = new MessageCatalogue($input->getArgument('locale'), $mergedTranslations);

        $output->writeln(sprintf('Write updates to the translation file'));

        $dumper = $app['translator.dumper'];
        $writer = new TranslationWriter();
        $writer->addDumper('xliff', $dumper);
        $writer->writeTranslations($catalogue, 'xliff',  array('path' => $app['translation.file_path'].$input->getArgument('locale')));

        $output->writeln('<info>Translation file for the locale '.$input->getArgument('locale').' Successfully updated !</info>');
    }
}