<?php
declare (strict_types = 1);

namespace Pmions\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Make extends Command {
	protected static $defaultName = 'make';

	protected function configure(): void {
		$this
			->setDescription('Creates a new migration or seeder')
			->addArgument('type', InputArgument::REQUIRED, 'Type of the creation (migration/seeder)')
             ->setHelp(sprintf(
                '%sCreates a new database migration%s',
                PHP_EOL,
                PHP_EOL
            ))
			->addOption('template', 't', InputOption::VALUE_REQUIRED, 'Name of the template or seeder');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int {
		$type = $input->getArgument('type');
		$template = $input->getOption('template');
		$filesystem = new Filesystem();

		if ($type === 'migration') { 
			$directory = 'database/migrations';
			$output->writeln("Creating migration: $template");

		} elseif ($type === 'seeder') {
			$directory = 'database/seeders';
			$output->writeln("Creating seeder: $template");
		} else {
			$output->writeln("Invalid type specified.");
			return Command::FAILURE;
		}

		if (!$filesystem->exists($directory)) {
			$filesystem->mkdir($directory);
		}

		if (substr($template, -1) == 's') {
			$tt = $template;
		    $table_id = substr($template, 0, -1); // Remove 's' para $table_id
		    
		} else {
		    $table_id = $template; // Mantém $template para $table_id
		    $tt = $template . 's'; // Adiciona 's' para CreateTableName e $table
		}

		// Caminho completo para o arquivo
		if ($type == 'migration') {

			$filePath = $directory . '/' . date('Y_m_d') . "_create_{$tt}_table" . '.php';
		} else if ($type == 'seeder') {
			$filePath = $directory . "/{$tt}" . '.php';
		}

		// Verifica se o arquivo já existe
		if ($filesystem->exists($filePath)) {
			$output->writeln("File already exists: $filePath");
			return Command::FAILURE;
		}

		// Cria o arquivo com conteúdo padrão
		$content = $this->fileContent($type, $template);

		$filesystem->dumpFile($filePath, $content);

		$output->writeln("Created $type file: $filePath");

		return Command::SUCCESS;
	}

	private function fileContent(string $type, string $template): string {
		// Diretório onde os arquivos estão localizados
		$directory = __DIR__ . '/../CommandsContent';



		// Crie um Finder para localizar os arquivos na pasta
		$finder = new Finder();
		$finder->files()->in($directory);

      $content = '';

      if (substr($template, -1) == 's') {
          $table_id = substr($template, 0, -1); // Remove 's' para $table_id
          
      } else {
          $table_id = $template; // Mantém $template para $table_id
          $template .= 's'; // Adiciona 's' para CreateTableName e $table
      }

		// Itera sobre os arquivos encontrados
		foreach ($finder as $file) {
	    	if ($type == 'migration' && $file->getFileName() === 'Migration') {
	        // Obtém o conteúdo do arquivo
	        	$content = $file->getContents();
	        
            // Substituições no conteúdo do arquivo
            $content = $this->replaceFields('\$class', "Create".ucfirst($template)."Table", $content);
            $content = $this->replaceFields('\$table', $template, $content);
            $content = $this->replaceFields('\$id', "{$table_id}_id", $content);
            return $content;
	      }
	      elseif ($type == 'seeder' && $file->getFileName() === 'Seeder') {
	      	$content = $file->getContents();
	        
            // Substituições no conteúdo do arquivo
            $content = $this->replaceFields('\$class', ucfirst($template), $content);
            $content = $this->replaceFields('\$table', $template, $content);
            return $content;
	      }
		}
	}

    private function replaceFields(string $search, string $replace, string $in) : string
    {
        return str_replace($search, $replace, $in);
    }
}
