<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260504210000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add price column to building (buildings.json).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE building ADD price SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE building DROP price');
    }
}
