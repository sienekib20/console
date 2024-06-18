<?php
declare(strict_types=1);

namespace Pmions\Console\Commands;

use Factory\Seeder\DatabaseSeeders;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Seed extends Command
{
    protected static $defaultName = 'seed';

    protected function configure(): void
    {
        $this
            ->setDescription('Runs seeders')
            ->addOption('all', 'a', InputOption::VALUE_NONE, 'Run all seeders')
            ->addOption('template', 't', InputOption::VALUE_REQUIRED, 'Run specific seeder by template name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directory = 'database/seeders';
        $filesystem = new Filesystem();
        $finder = new Finder();

        if ($input->getOption('all')) {

            $databaseSeeder = new DatabaseSeeders();
            $databaseSeeder->execute();
           // return $this->runAllSeeders($output, $filesystem, $finder, $directory);
        } elseif ($template = $input->getOption('template')) {
            //return $this->runTemplateSeeder($output, $filesystem, $finder, $directory, $template);
            $output->writeln('Not Implements Yet.');
        } else {
            $output->writeln('Please specify an option to run seeders.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function runAllSeeders(OutputInterface $output, Filesystem $filesystem, Finder $finder, string $directory): int
    {
        $finder->files()->in($directory);

        foreach ($finder as $file) {
            if (!$filesystem->exists($file->getPathname())) {
                $output->writeln("<error>Invalid seeder file: {$file->getPathname()}</error>");
                return Command::FAILURE;
            }

            require $file->getPathname();

            $onlyName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $seederName = ucfirst($this->extractSeederName($file->getFilename()));
            $seederClass = "{$seederName}";

            if (!class_exists($seederClass)) {
                $output->writeln("<error>Seeder class not found: {$seederClass}</error>");
                continue;
            }

            $seederInstance = new $seederClass();

            $output->writeln("Running seeder: {$onlyName}");
            $seederInstance->seed();
            $output->writeln("Finished seeding: {$onlyName}");
            sleep(1);
        }

        $output->writeln('Applied all seeders.');
        return Command::SUCCESS;
    }

    private function runTemplateSeeder(OutputInterface $output, Filesystem $filesystem, Finder $finder, string $directory, string $template): int
    {
        $finder->files()->in($directory)->name("*{$template}*");

        if ($finder->count() === 0) {
            $output->writeln("<error>No seeder files found for template: {$template}</error>");
            return Command::FAILURE;
        }

        foreach ($finder as $file) {
            if (!$filesystem->exists($file->getPathname())) {
                $output->writeln("<error>Invalid seeder file: {$file->getPathname()}</error>");
                return Command::FAILURE;
            }

            require $file->getPathname();

            $onlyName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $seederName = ucfirst($this->extractSeederName($file->getFilename()));
            $seederClass = "{$seederName}";

            if (!class_exists($seederClass)) {
                $output->writeln("<error>Seeder class not found: {$seederClass}</error>");
                return Command::FAILURE;
            }

            $seederInstance = new $seederClass();

            $output->writeln("Running seeder: {$onlyName}");
            $seederInstance->seed();
            $output->writeln("Finished seeding: {$onlyName}");
            sleep(1);
        }

        $output->writeln("Applied seeder for template: {$template}.");
        return Command::SUCCESS;
    }

    private function extractSeederName(string $filename): string
    {
        $parts = explode('_', pathinfo($filename, PATHINFO_FILENAME));
        return end($parts);
    }
}
