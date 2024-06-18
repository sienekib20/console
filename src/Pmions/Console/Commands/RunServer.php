<?php
declare(strict_types=1);

namespace Pmions\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class RunServer extends Command {
    protected static $defaultName = 'run';

    protected function configure(): void {
        $this
            ->setDescription('Run the master server')
            ->addOption('host', null, InputOption::VALUE_REQUIRED, 'Host to run the server', '127.0.0.1:8000')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force the server to run')
            ->addOption('tuner', null, InputOption::VALUE_NONE, 'Enable tuner mode')
            ->setHelp(sprintf(
                '%sRun the master server with optional configurations.%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $host = $input->getOption('host');
        $force = $input->getOption('force');
        $tuner = $input->getOption('tuner');

        $output->writeln("\033[33mRunning server on $host\033[0m");

        if ($force) {
            $output->writeln("Force mode enabled");
        }

        if ($tuner) {
            $output->writeln("Tuner mode enabled");
        }

        // Command to run the server
        $command = sprintf('php -S %s', $host);

        // Add additional flags based on options
        if ($force) {
            $command .= ' --force';
        }

        if ($tuner) {
            $command .= ' --tuner';
        }


        // Use Process to run the command
        $process = Process::fromShellCommandline($command);
        $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        if (!$process->isSuccessful()) {
            $output->writeln('<error>Failed to start the server.</error>');
            return Command::FAILURE;
        }

        $output->writeln('Server started successfully.');

        return Command::SUCCESS;
    }
}
