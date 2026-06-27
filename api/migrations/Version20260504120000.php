<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Aligne les colonnes datetime avec le schéma attendu par Doctrine (validate schema).
 */
final class Version20260504120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Normalize creation/modification datetime columns for schema validation.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `character` CHANGE creation creation DATETIME NOT NULL, CHANGE modification modification DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `character` MODIFY creation DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'");
        $this->addSql("ALTER TABLE `character` MODIFY modification DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'");
    }
}
