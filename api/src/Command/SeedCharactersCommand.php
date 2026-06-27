<?php

namespace App\Command;

use App\Service\CharacterServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:character:seed',
    description: 'Insère les personnages par défaut (uniquement les slugs absents de la base).',
)]
final class SeedCharactersCommand extends Command
{
    public function __construct(
        private readonly CharacterServiceInterface $characterService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inserted = $this->characterService->seedDefaults();

        if ($inserted === []) {
            $io->success('Rien à ajouter : tous les personnages du jeu par défaut sont déjà présents.');

            return Command::SUCCESS;
        }

        $io->success(sprintf('%d personnage(s) ajouté(s).', count($inserted)));
        foreach ($inserted as $character) {
            $io->writeln(sprintf(' - %s (%s)', (string) $character->getName(), (string) $character->getSlug()));
        }

        return Command::SUCCESS;
    }
}
