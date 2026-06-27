<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260601120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create character table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, identifier VARCHAR(40) NOT NULL, name VARCHAR(20) NOT NULL, slug VARCHAR(20) NOT NULL, kind VARCHAR(20) NOT NULL, surname VARCHAR(50) NOT NULL, caste VARCHAR(20) DEFAULT NULL, knowledge VARCHAR(20) DEFAULT NULL, intelligence SMALLINT DEFAULT NULL, strength SMALLINT DEFAULT NULL, image VARCHAR(50) DEFAULT NULL, creation DATETIME NOT NULL, modification DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `character`');
    }
}
