<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Données initiales building (équivalent buildings.json) : les migrations ne chargent pas les fixtures.
 */
final class Version20260505140000 extends AbstractMigration
{
    /**
     * @return list<array{name: string, slug: string, caste: string, strength: int, price: int|null, image: string}>
     */
    private function seedRows(): array
    {
        return [
            ['name' => 'Château Lenora', 'slug' => 'chateau-lenora', 'caste' => 'Guerrier', 'strength' => 1000, 'price' => 200, 'image' => '/buildings/chateau-lenora.webp'],
            ['name' => 'Château Silken', 'slug' => 'chateau-silken', 'caste' => 'Archer', 'strength' => 1200, 'price' => 240, 'image' => '/buildings/chateau-silken.webp'],
            ['name' => 'Château Antoleme', 'slug' => 'chateau-antoleme', 'caste' => 'Chevalier', 'strength' => 1400, 'price' => 280, 'image' => '/buildings/chateau-antoleme.webp'],
            ['name' => 'Château Unflune', 'slug' => 'chateau-unflune', 'caste' => 'Elfe', 'strength' => 1600, 'price' => 320, 'image' => '/buildings/chateau-unflune.webp'],
            ['name' => 'Château Stamlam', 'slug' => 'chateau-stamlam', 'caste' => 'Magicien', 'strength' => 1800, 'price' => 360, 'image' => '/buildings/chateau-stamlam.webp'],
            ['name' => 'Château Oakenfield', 'slug' => 'chateau-oakenfield', 'caste' => 'Erudit', 'strength' => 2000, 'price' => 400, 'image' => '/buildings/chateau-oakenfield.webp'],
            ['name' => 'Château Hurlton', 'slug' => 'chateau-hurlton', 'caste' => 'Gobelin', 'strength' => 10_000, 'price' => null, 'image' => '/buildings/chateau-hurlton.webp'],
            ['name' => 'Château Kaerndal', 'slug' => 'chateau-kaerndal', 'caste' => 'Bourreau', 'strength' => 10_000, 'price' => null, 'image' => '/buildings/chateau-kaerndal.webp'],
            ['name' => 'Château Merclefield', 'slug' => 'chateau-merclefield', 'caste' => 'Enchanteur', 'strength' => 15_000, 'price' => null, 'image' => '/buildings/chateau-merclefield.webp'],
            ['name' => 'Château Direnwood', 'slug' => 'chateau-direnwood', 'caste' => 'ElfeNoir', 'strength' => 15_000, 'price' => null, 'image' => '/buildings/chateau-direnwood.webp'],
            ['name' => 'Château Dragonspire', 'slug' => 'chateau-dragonspire', 'caste' => 'Sorcier', 'strength' => 20_000, 'price' => null, 'image' => '/buildings/chateau-dragonspire.webp'],
            ['name' => 'Château Perrigwyn', 'slug' => 'chateau-perrigwyn', 'caste' => 'Mage', 'strength' => 20_000, 'price' => null, 'image' => '/buildings/chateau-perrigwyn.webp'],
        ];
    }

    public function getDescription(): string
    {
        return 'Seed building rows from buildings.json (INSERT IGNORE).';
    }

    public function up(Schema $schema): void
    {
        $conn = $this->connection;
        foreach ($this->seedRows() as $row) {
            $identifier = hash('sha1', 'migration-seed-building:'.$row['slug']);
            $priceSql = $row['price'] === null ? 'NULL' : (string) (int) $row['price'];
            $this->addSql(sprintf(
                'INSERT IGNORE INTO building (identifier, name, slug, caste, strength, price, image, creation, modification) VALUES (%s, %s, %s, %s, %d, %s, %s, NOW(), NOW())',
                $conn->quote($identifier),
                $conn->quote($row['name']),
                $conn->quote($row['slug']),
                $conn->quote($row['caste']),
                $row['strength'],
                $priceSql,
                $conn->quote($row['image'])
            ));
        }
    }

    public function down(Schema $schema): void
    {
        $conn = $this->connection;
        foreach ($this->seedRows() as $row) {
            $identifier = hash('sha1', 'migration-seed-building:'.$row['slug']);
            $this->addSql(
                'DELETE FROM building WHERE identifier = '.$conn->quote($identifier)
            );
        }
    }
}
