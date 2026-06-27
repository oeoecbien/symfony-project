<?php

namespace App\Command;

use App\Repository\CharacterRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;

#[AsCommand(
    name: 'app:create-sitemaps',
    description: 'Create sitemap index and site sitemap files in public/',
)]
class CreateSitemapsCommand extends Command
{
    public const BASE_URL = 'https://la-guilde-des-seigneurs.com';

    public const FOLDER = __DIR__.'/../../public';

    public function __construct(
        private readonly CharacterRepository $characterRepository,
        private readonly Environment $env,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->createSitemapIndex();
        $this->createSitemapSite();

        $io->success('Sitemaps created!');

        return Command::SUCCESS;
    }

    public function createSitemapIndex(): void
    {
        $sitemaps = [
            self::BASE_URL.'/sitemap-site.xml',
        ];

        $sitemapIndexContent = $this->env->render(
            'sitemaps/sitemap-index.xml.twig',
            ['sitemaps' => $sitemaps]
        );

        $sitemapIndexFile = self::FOLDER.'/sitemap-index.xml';
        file_put_contents($sitemapIndexFile, $sitemapIndexContent);
    }

    public function createSitemapSite(): void
    {
        $pagesList = $this->getPages();

        $pages = [];
        foreach ($pagesList as $url => $value) {
            $values = explode(',', $value);
            $pages[] = [
                'loc' => self::BASE_URL.'/'.$url,
                'lastmod' => null,
                'changefreq' => trim($values[0]),
                'priority' => trim($values[1]),
            ];
        }

        $sitemapContent = $this->env->render(
            'sitemaps/sitemap.xml.twig',
            ['pages' => $pages]
        );

        $sitemapFile = self::FOLDER.'/sitemap-site.xml';
        file_put_contents($sitemapFile, $sitemapContent);
    }

    /**
     * @return array<string, string>
     */
    public function getPages(): array
    {
        $pagesList = [
            '' => 'weekly, 1.0',
            'character' => 'weekly, 0.9',
        ];

        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character) {
            $pagesList['character/'.$character->getId()] = 'weekly, 0.8';
        }

        return $pagesList;
    }
}
