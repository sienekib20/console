<?php

declare(strict_types=1);

namespace Pmions\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Migrate extends Command
{
    protected static $defaultName = 'migrate';

    protected function configure(): void
    {
        $this
            ->setDescription('Runs migrations')
            ->addOption('all', 'a', InputOption::VALUE_NONE, 'Run all migrations')
            ->addOption('template', 't', InputOption::VALUE_REQUIRED, 'Run specific migration by template name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directory = 'database/migrations';
        $filesystem = new Filesystem();
        $finder = new Finder();

        if ($input->getOption('all')) {
            return $this->runAllMigrations($output, $filesystem, $finder, $directory);
        } elseif ($template = $input->getOption('template')) {
            return $this->runTemplateMigration($output, $filesystem, $finder, $directory, $template);
        } else {
            $output->writeln('Please specify an option to run migrations.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function runAllMigrations(OutputInterface $output, Filesystem $filesystem, Finder $finder, string $directory): int
    {
        $finder->files()->in($directory);

        foreach ($finder as $file) {
            if (!$filesystem->exists($file->getPathname())) {
                $output->writeln("<error>Invalid migration file: {$file->getPathname()}</error>");
                return Command::FAILURE;
            }

            require $file->getPathname();

            $onlyName =  explode(
                '_',
                pathinfo($file->getFilename(), PATHINFO_FILENAME)
            )[4];
            $migrationName = ucfirst($this->extractMigrationName($file->getFilename()));
            $migrationClass = "Create{$onlyName}Table";

            if (!class_exists($migrationClass)) {
                $output->writeln("<error>Migration class not found: {$migrationClass}</error>");
                continue;
            }

            $migrationInstance = new $migrationClass();


            $output->writeln("Creating table: {$onlyName}");
            $migrationInstance->down();
            sleep(1);
            $migrationInstance->up();
            $output->writeln("Created table: {$onlyName}");
            sleep(1);
        }

        $output->writeln('Applied all migrations.');
        return Command::SUCCESS;
    }

    private function runTemplateMigration(OutputInterface $output, Filesystem $filesystem, Finder $finder, string $directory, string $template): int
    {
        $finder->files()->in($directory)->name("*{$template}*");

        if ($finder->count() === 0) {
            $output->writeln("<error>No migration files found for template: {$template}</error>");
            return Command::FAILURE;
        }

        foreach ($finder as $file) {
            if (!$filesystem->exists($file->getPathname())) {
                $output->writeln("<error>Invalid migration file: {$file->getPathname()}</error>");
                return Command::FAILURE;
            }

            require $file->getPathname();

            $onlyName =  explode(
                '_',
                pathinfo($file->getFilename(), PATHINFO_FILENAME)
            )[4];
            $migrationName = ucfirst($this->extractMigrationName($file->getFilename()));
            $migrationClass = "Create{$onlyName}Table";

            if (!class_exists($migrationClass)) {
                $output->writeln("<error>Migration class not found: {$migrationClass}</error>");
                return Command::FAILURE;
            }

            $migrationInstance = new $migrationClass();

            $output->writeln("Creating table: {$onlyName}");
            $migrationInstance->down();
            sleep(1);
            $migrationInstance->up();
            $output->writeln("Created table: {$onlyName}");
            sleep(1);
        }

        $output->writeln("Applied migration for template: {$template}.");
        return Command::SUCCESS;
    }

    private function extractMigrationName(string $filename): string
    {
        $parts = explode('_', pathinfo($filename, PATHINFO_FILENAME));
        return end($parts);
    }
}
