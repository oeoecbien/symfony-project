<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:database:refresh',
    description: 'Supprime le schéma, rejoue les migrations et charge les fixtures (dev).',
)]
final class RefreshDatabaseCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $app = $this->getApplication();
        if ($app === null) {
            $io->error('Application console indisponible.');

            return Command::FAILURE;
        }

        $kernel = $app->getKernel();
        if (!$kernel instanceof KernelInterface) {
            $io->error('Kernel Symfony attendu.');

            return Command::FAILURE;
        }

        $projectDir = $kernel->getProjectDir();
        $php = \PHP_BINARY;

        $io->warning('Toutes les tables seront supprimées puis recréées (données perdues).');

        $steps = [
            [$php, $projectDir.'/bin/console', 'doctrine:schema:drop', '--full-database', '--force', '-n'],
            [$php, $projectDir.'/bin/console', 'doctrine:migrations:migrate', '-n'],
            [$php, $projectDir.'/bin/console', 'doctrine:fixtures:load', '-n'],
        ];

        foreach ($steps as $command) {
            $process = new Process($command, $projectDir);
            $process->setTimeout(null);
            $process->run(static function (string $type, string $buffer) use ($output): void {
                $output->write($buffer);
            });
            if (!$process->isSuccessful()) {
                $io->error(trim($process->getErrorOutput()."\n".$process->getOutput()));

                return Command::FAILURE;
            }
        }

        $io->success('Base nettoyée, migrations appliquées, fixtures importées.');

        return Command::SUCCESS;
    }
}
