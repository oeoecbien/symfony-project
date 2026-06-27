<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Création de la table `character` (entité Character).
 */
final class Version20260330140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `character` table for Character entity.';
    }

    public function up(Schema $schema): void
    {
        // IF NOT EXISTS : base déjà initialisée par une ancienne migration supprimée du projet.
        $this->addSql('CREATE TABLE IF NOT EXISTS `character` (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(20) NOT NULL,
            slug VARCHAR(20) NOT NULL,
            kind VARCHAR(20) NOT NULL,
            surname VARCHAR(50) NOT NULL,
            caste VARCHAR(20) DEFAULT NULL,
            knowledge VARCHAR(20) DEFAULT NULL,
            intelligence SMALLINT DEFAULT NULL,
            strength SMALLINT DEFAULT NULL,
            image VARCHAR(50) DEFAULT NULL,
            creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `character`');
    }
}
