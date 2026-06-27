<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260504200000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create building table for Building entity.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE building (
            id INT AUTO_INCREMENT NOT NULL,
            identifier VARCHAR(40) NOT NULL,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(40) NOT NULL,
            caste VARCHAR(40) NOT NULL,
            strength SMALLINT NOT NULL,
            image VARCHAR(120) DEFAULT NULL,
            creation DATETIME NOT NULL,
            modification DATETIME NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BUILDING_IDENTIFIER ON building (identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BUILDING_SLUG ON building (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE building');
    }
}
