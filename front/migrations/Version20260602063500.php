<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260602063500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add life field on Character';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` ADD life SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` DROP life');
    }
}
