<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260330170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add non-predictable identifier to character table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` ADD identifier VARCHAR(40) DEFAULT NULL');
        $this->addSql("UPDATE `character` SET identifier = SHA1(CONCAT('legacy-', id)) WHERE identifier IS NULL");
        $this->addSql('ALTER TABLE `character` MODIFY identifier VARCHAR(40) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHARACTER_IDENTIFIER ON `character` (identifier)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_CHARACTER_IDENTIFIER ON `character`');
        $this->addSql('ALTER TABLE `character` DROP identifier');
    }
}
