<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260504000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add modification datetime to `character` table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` ADD modification DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('UPDATE `character` SET modification = creation WHERE modification IS NULL');
        $this->addSql('ALTER TABLE `character` MODIFY modification DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` DROP modification');
    }
}
