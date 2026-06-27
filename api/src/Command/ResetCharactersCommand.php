<?php

namespace App\Command;

use App\Service\CharacterServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:character:reset',
    description: 'Vide la table des personnages et réinsère le jeu par défaut (sans trous dans les id).',
)]
final class ResetCharactersCommand extends Command
{
    public function __construct(
        private readonly CharacterServiceInterface $characterService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inserted = $this->characterService->resetAndSeed();

        $io->success(sprintf('Table réinitialisée : %d personnage(s) inséré(s).', count($inserted)));
        foreach ($inserted as $character) {
            $io->writeln(sprintf(
                ' - id %s - %s (%s)',
                (string) ($character->getId() ?? '?'),
                (string) $character->getName(),
                (string) $character->getSlug()
            ));
        }

        return Command::SUCCESS;
    }
}
