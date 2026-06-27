<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260602062645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add life field on Character';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` ADD gls_life SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` DROP gls_life');
    }
}
